<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show profile edit form for authenticated user.
     */
    public function edit(): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user's name and/or password.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        if (!empty($data['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
