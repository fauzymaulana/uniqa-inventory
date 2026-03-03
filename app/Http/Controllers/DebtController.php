<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Debtor;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class DebtController extends Controller
{
    /**
     * Riwayat Hutang – list semua penghutang beserta total hutangnya.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all'); // all | unpaid | paid

        $query = Debtor::withCount('debts')
            ->withSum('debts', 'amount')
            ->withSum(['debts as unpaid_total' => fn($q) => $q->where('is_paid', false)], 'amount')
            ->withSum(['debts as unpaid_paid'  => fn($q) => $q->where('is_paid', false)], 'amount_paid')
            ->when($search, fn($q) => $q->where('name', 'ilike', "%{$search}%"))
            ->when($filter === 'unpaid', fn($q) => $q->whereHas('debts', fn($q2) => $q2->where('is_paid', false)))
            ->when($filter === 'paid', fn($q) => $q->whereHas('debts')->whereDoesntHave('debts', fn($q2) => $q2->where('is_paid', false)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Summary stats
        $totalDebtors      = Debtor::count();
        $totalOutstanding  = Debt::where('is_paid', false)->sum('amount')
                           - Debt::where('is_paid', false)->sum('amount_paid');
        $totalPaid         = Debt::where('is_paid', true)->sum('amount');
        $overdueCount      = Debt::overdue()->count();

        return view('debts.index', compact(
            'query', 'search', 'filter',
            'totalDebtors', 'totalOutstanding', 'totalPaid', 'overdueCount'
        ));
    }

    /**
     * Detail satu penghutang: semua catatan hutangnya.
     */
    public function show(Debtor $debtor): View
    {
        $debts = $debtor->debts()
            ->with(['recorder', 'paidByUser', 'payments.recorder'])
            ->latest()
            ->get();

        $totalOutstanding = $debtor->debts()->where('is_paid', false)->sum('amount')
                          - $debtor->debts()->where('is_paid', false)->sum('amount_paid');
        $totalPaid        = $debtor->debts()->where('is_paid', true)->sum('amount');
        // partial paid on unpaid debts
        $totalPartialPaid = $debtor->debts()->where('is_paid', false)->sum('amount_paid');
        $totalDebt        = $debtor->debts()->sum('amount');
        $totalAllPaid     = $debtor->debts()->sum('amount_paid');

        return view('debts.show', compact(
            'debtor', 'debts',
            'totalOutstanding', 'totalPaid', 'totalPartialPaid',
            'totalDebt', 'totalAllPaid'
        ));
    }

    /**
     * Form tambah hutang baru (bisa pilih penghutang yang sudah ada atau buat baru).
     */
    public function create(): View
    {
        $debtors = Debtor::orderBy('name')->get();
        return view('debts.create', compact('debtors'));
    }

    /**
     * Simpan catatan hutang baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'debtor_type'    => 'required|in:existing,new',
            'debtor_id'      => 'required_if:debtor_type,existing|nullable|exists:debtors,id',
            'debtor_name'    => 'required_if:debtor_type,new|nullable|string|max:255',
            'debtor_phone'   => 'nullable|string|max:20',
            'debtor_address' => 'nullable|string|max:500',
            'amount'         => 'required|numeric|min:1',
            'due_date'       => 'required|date|after_or_equal:today',
            'description'    => 'nullable|string|max:1000',
        ], [
            'debtor_id.required_if'   => 'Pilih nama penghutang.',
            'debtor_name.required_if' => 'Nama penghutang baru wajib diisi.',
            'amount.required'         => 'Jumlah hutang wajib diisi.',
            'amount.min'              => 'Jumlah hutang minimal Rp 1.',
            'due_date.required'       => 'Tenggat waktu wajib diisi.',
            'due_date.after_or_equal' => 'Tenggat waktu tidak boleh di masa lalu.',
        ]);

        // Tentukan atau buat debtor
        if ($request->debtor_type === 'new') {
            $debtor = Debtor::create([
                'name'    => $request->debtor_name,
                'phone'   => $request->debtor_phone,
                'address' => $request->debtor_address,
            ]);
        } else {
            $debtor = Debtor::findOrFail($request->debtor_id);
        }

        // Buat catatan hutang
        Debt::create([
            'debtor_id'   => $debtor->id,
            'user_id'     => auth()->id(),
            'amount'      => $request->amount,
            'due_date'    => $request->due_date,
            'is_paid'     => false,
            'description' => $request->description,
        ]);

        $role = auth()->user()->role;
        return redirect()
            ->route("{$role}.debts.show", $debtor)
            ->with('success', "Hutang atas nama {$debtor->name} berhasil dicatat.");
    }

    /**
     * Lunaskan SEMUA hutang yang belum lunas milik satu penghutang sekaligus.
     */
    public function payAll(Debtor $debtor): RedirectResponse
    {
        $unpaid = $debtor->debts()->where('is_paid', false)->get();

        if ($unpaid->isEmpty()) {
            return back()->with('error', 'Tidak ada hutang yang perlu dilunaskan.');
        }

        foreach ($unpaid as $debt) {
            $remaining = (float) $debt->amount - (float) $debt->amount_paid;
            if ($remaining > 0) {
                // Catat cicilan pelunasan penuh
                DebtPayment::create([
                    'debt_id' => $debt->id,
                    'user_id' => auth()->id(),
                    'amount'  => $remaining,
                    'note'    => 'Pelunasan penuh (bayar semua)',
                ]);
            }
            $debt->update([
                'amount_paid' => $debt->amount,
                'is_paid'     => true,
                'paid_at'     => now(),
                'paid_by'     => auth()->id(),
            ]);
        }

        $role = auth()->user()->role;
        return redirect()
            ->route("{$role}.debts.show", $debtor)
            ->with('success', "Semua hutang {$debtor->name} telah dilunaskan.");
    }

    /**
     * Lunaskan satu catatan hutang penuh sekaligus.
     */
    public function payOne(Debt $debt): RedirectResponse
    {
        if ($debt->is_paid) {
            return back()->with('error', 'Hutang ini sudah lunas.');
        }

        $remaining = (float) $debt->amount - (float) $debt->amount_paid;

        if ($remaining > 0) {
            DebtPayment::create([
                'debt_id' => $debt->id,
                'user_id' => auth()->id(),
                'amount'  => $remaining,
                'note'    => 'Pelunasan penuh',
            ]);
        }

        $debt->update([
            'amount_paid' => $debt->amount,
            'is_paid'     => true,
            'paid_at'     => now(),
            'paid_by'     => auth()->id(),
        ]);

        $role = auth()->user()->role;
        return redirect()
            ->route("{$role}.debts.show", $debt->debtor)
            ->with('success', 'Hutang berhasil dilunaskan penuh.');
    }

    /**
     * Bayar sebagian (cicilan) untuk satu catatan hutang.
     */
    public function payPartial(Request $request, Debt $debt): RedirectResponse
    {
        if ($debt->is_paid) {
            return back()->with('error', 'Hutang ini sudah lunas.');
        }

        $remaining = (float) $debt->amount - (float) $debt->amount_paid;

        $request->validate([
            'pay_amount' => ['required', 'numeric', 'min:1', 'max:' . $remaining],
            'pay_note'   => ['nullable', 'string', 'max:500'],
        ], [
            'pay_amount.required' => 'Jumlah pembayaran wajib diisi.',
            'pay_amount.min'      => 'Jumlah pembayaran minimal Rp 1.',
            'pay_amount.max'      => 'Jumlah pembayaran tidak boleh melebihi sisa hutang (Rp ' . number_format($remaining, 0, ',', '.') . ').',
        ]);

        // Catat cicilan
        DebtPayment::create([
            'debt_id' => $debt->id,
            'user_id' => auth()->id(),
            'amount'  => $request->pay_amount,
            'note'    => $request->pay_note,
        ]);

        // Update amount_paid
        $newPaid = (float) $debt->amount_paid + (float) $request->pay_amount;
        $isNowPaid = $newPaid >= (float) $debt->amount;

        $debt->update([
            'amount_paid' => $newPaid,
            'is_paid'     => $isNowPaid,
            'paid_at'     => $isNowPaid ? now() : $debt->paid_at,
            'paid_by'     => $isNowPaid ? auth()->id() : $debt->paid_by,
        ]);

        $role = auth()->user()->role;
        $msg  = $isNowPaid
            ? 'Pembayaran diterima — hutang telah LUNAS!'
            : 'Cicilan Rp ' . number_format($request->pay_amount, 0, ',', '.') . ' berhasil dicatat.';

        return redirect()
            ->route("{$role}.debts.show", $debt->debtor)
            ->with('success', $msg);
    }

    /**
     * Hapus satu catatan hutang.
     */
    public function destroy(Debt $debt): RedirectResponse
    {
        $debtor = $debt->debtor;
        $debt->delete();

        $role = auth()->user()->role;
        return redirect()
            ->route("{$role}.debts.show", $debtor)
            ->with('success', 'Catatan hutang berhasil dihapus.');
    }

    /**
     * Hapus penghutang beserta semua catatannya.
     */
    public function destroyDebtor(Debtor $debtor): RedirectResponse
    {
        $debtor->delete(); // cascade akan hapus debts juga

        $role = auth()->user()->role;
        return redirect()
            ->route("{$role}.debts.index")
            ->with('success', "Data penghutang {$debtor->name} berhasil dihapus.");
    }

    /**
     * Sync endpoint: terima debt record dari offline queue (JSON/FormData).
     */
    public function syncOffline(Request $request)
    {
        $request->validate([
            'debtor_type'    => 'required|in:existing,new',
            'debtor_id'      => 'required_if:debtor_type,existing|nullable|exists:debtors,id',
            'debtor_name'    => 'required_if:debtor_type,new|nullable|string|max:255',
            'debtor_phone'   => 'nullable|string|max:20',
            'debtor_address' => 'nullable|string|max:500',
            'amount'         => 'required|numeric|min:1',
            'due_date'       => 'required|date',
            'description'    => 'nullable|string|max:1000',
        ]);

        if ($request->debtor_type === 'new') {
            $debtor = Debtor::firstOrCreate(
                ['name' => $request->debtor_name],
                ['phone' => $request->debtor_phone, 'address' => $request->debtor_address]
            );
        } else {
            $debtor = Debtor::findOrFail($request->debtor_id);
        }

        $debt = Debt::create([
            'debtor_id'   => $debtor->id,
            'user_id'     => auth()->id(),
            'amount'      => $request->amount,
            'due_date'    => $request->due_date,
            'is_paid'     => false,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'debt_id' => $debt->id, 'debtor_id' => $debtor->id]);
    }

    /**
     * Sync endpoint: terima cicilan dari offline queue.
     */
    public function syncOfflinePayment(Request $request, Debt $debt)
    {
        $remaining = (float) $debt->amount - (float) $debt->amount_paid;

        $request->validate([
            'pay_amount' => ['required', 'numeric', 'min:1', 'max:' . $remaining],
            'pay_note'   => ['nullable', 'string', 'max:500'],
        ]);

        DebtPayment::create([
            'debt_id' => $debt->id,
            'user_id' => auth()->id(),
            'amount'  => $request->pay_amount,
            'note'    => $request->pay_note,
        ]);

        $newPaid   = (float) $debt->amount_paid + (float) $request->pay_amount;
        $isNowPaid = $newPaid >= (float) $debt->amount;

        $debt->update([
            'amount_paid' => $newPaid,
            'is_paid'     => $isNowPaid,
            'paid_at'     => $isNowPaid ? now() : $debt->paid_at,
            'paid_by'     => $isNowPaid ? auth()->id() : $debt->paid_by,
        ]);

        return response()->json(['success' => true, 'is_paid' => $isNowPaid]);
    }
}
