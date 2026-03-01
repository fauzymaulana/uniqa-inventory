@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.expenses.index') : route('cashier.expenses.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Offline Banner -->
        <div id="offlineBanner" class="alert alert-warning d-none mb-3">
            <i class="fas fa-wifi-slash me-2"></i> <strong>Anda sedang offline.</strong> Pengeluaran akan disimpan sementara dan disinkronkan otomatis saat koneksi kembali.
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Tambah Pengeluaran</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ auth()->user()->role === 'admin' ? route('admin.expenses.store') : route('cashier.expenses.store') }}" method="POST" id="expenseForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kegiatan Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="activity" id="expActivity" class="form-control @error('activity') is-invalid @enderror" 
                            placeholder="Contoh: Fotokopi, Pembersihan, Pembelian alat..." value="{{ old('activity') }}" required>
                        @error('activity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select name="type" id="expType" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="operasional" @selected(old('type') === 'operasional')>Operasional</option>
                            <option value="asset" @selected(old('type') === 'asset')>Asset</option>
                            <option value="stok_barang" @selected(old('type') === 'stok_barang')>Stok Barang</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori Produk</label>
                        <select name="category_id" id="expCategoryId" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="expStatus" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="selesai" @selected(old('status') === 'selesai')>Selesai</option>
                            <option value="belum_tuntas" @selected(old('status') === 'belum_tuntas')>Belum Tuntas</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biaya (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="expAmount" class="form-control @error('amount') is-invalid @enderror" 
                            placeholder="0" step="0.01" min="0" value="{{ old('amount', 0) }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" id="expDescription" class="form-control @error('description') is-invalid @enderror" 
                            rows="3" placeholder="Masukkan keterangan atau catatan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.expenses.index') : route('cashier.expenses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if(auth()->user()->role === 'cashier')
<script>
const EXPENSE_DB_NAME = 'uniqa_expense_offline';
const EXPENSE_STORE = 'pending_expenses';

function openExpenseDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open(EXPENSE_DB_NAME, 1);
        req.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains(EXPENSE_STORE)) {
                db.createObjectStore(EXPENSE_STORE, { keyPath: 'offline_id', autoIncrement: true });
            }
        };
        req.onsuccess = (e) => resolve(e.target.result);
        req.onerror = (e) => reject(e);
    });
}

async function savePendingExpense(data) {
    const db = await openExpenseDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(EXPENSE_STORE, 'readwrite');
        const store = tx.objectStore(EXPENSE_STORE);
        const req = store.add({ ...data, saved_at: new Date().toISOString() });
        req.onsuccess = () => resolve(req.result);
        req.onerror = (e) => reject(e);
    });
}

function updateNetworkStatus() {
    const banner = document.getElementById('offlineBanner');
    if (!navigator.onLine) {
        banner.classList.remove('d-none');
    } else {
        banner.classList.add('d-none');
        syncPendingExpenses();
    }
}

async function syncPendingExpenses() {
    if (!navigator.onLine) return;
    const db = await openExpenseDB();
    const pending = await new Promise((resolve, reject) => {
        const tx = db.transaction(EXPENSE_STORE, 'readonly');
        const req = tx.objectStore(EXPENSE_STORE).getAll();
        req.onsuccess = () => resolve(req.result);
        req.onerror = (e) => reject(e);
    });
    if (!pending.length) return;

    try {
        const resp = await fetch('{{ route("api.sync-expenses") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ expenses: pending }),
        });
        const result = await resp.json();
        if (result.success) {
            for (const s of result.synced) {
                await new Promise((resolve) => {
                    const tx = db.transaction(EXPENSE_STORE, 'readwrite');
                    tx.objectStore(EXPENSE_STORE).delete(s.offline_id);
                    tx.oncomplete = resolve;
                });
            }
        }
    } catch {}
}

document.getElementById('expenseForm').addEventListener('submit', async function(e) {
    if (navigator.onLine) return; // let normal submit proceed

    e.preventDefault();

    const data = {
        activity: document.getElementById('expActivity').value,
        type: document.getElementById('expType').value,
        category_id: document.getElementById('expCategoryId').value || null,
        status: document.getElementById('expStatus').value,
        amount: document.getElementById('expAmount').value,
        description: document.getElementById('expDescription').value,
    };

    if (!data.activity || !data.type || !data.status || !data.amount) {
        alert('Harap isi semua field yang wajib diisi.');
        return;
    }

    await savePendingExpense(data);
    alert('💾 Pengeluaran disimpan offline. Akan disinkronkan otomatis saat online.');
    window.location.href = '{{ auth()->user()->role === "admin" ? route("admin.expenses.index") : route("cashier.expenses.index") }}';
});

window.addEventListener('online', updateNetworkStatus);
window.addEventListener('offline', updateNetworkStatus);
updateNetworkStatus();
</script>
@endif
@endsection
