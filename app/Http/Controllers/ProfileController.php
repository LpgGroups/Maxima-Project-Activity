<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class ProfileController extends Controller
{
    public function edit()
    {
        return view('dashboard.profile.edit', [
            'user' => Auth::user(),
            'title' => 'Edit Profil Saya',
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi form
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'company'  => 'nullable|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed', // jika user isi password
        ], [
            'name.required' => 'Nama wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Update field yang boleh
        $user->name = $request->name;
        $user->company = $request->company;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('status', 'Profil berhasil diperbarui!');
    }
}
