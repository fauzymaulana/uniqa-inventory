<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function index()
    {
        $cashiers = User::where('role', 'cashier')->get();
        return view('admin.cashiers.index', compact('cashiers'));
    }

    public function create()
    {
        return view('admin.cashiers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'cashier',
        ]);

        return redirect()->route('admin.cashiers.index')->with('success', 'Akun kasir berhasil dibuat');
    }

    public function edit(User $cashier)
    {
        return view('admin.cashiers.edit', compact('cashier'));
    }

    public function update(Request $request, User $cashier)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $cashier->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $cashier->name = $data['name'];
        $cashier->email = $data['email'];
        if (!empty($data['password'])) {
            $cashier->password = Hash::make($data['password']);
        }
        $cashier->save();

        return redirect()->route('admin.cashiers.index')->with('success', 'Data kasir berhasil diperbarui');
    }

    public function destroy(User $cashier)
    {
        $cashier->delete();
        return redirect()->route('admin.cashiers.index')->with('success', 'Kasir dihapus');
    }
}
