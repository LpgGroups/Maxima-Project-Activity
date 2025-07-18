<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email wajib di isi',
                'password.required' => 'Password wajib di isi',
            ]
        );

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            // Periksa apakah akun aktif
            if (!Auth::user()->is_active) {
                Auth::logout();
                return redirect('/')->withErrors('Akun Anda tidak aktif. Silakan hubungi administrator.');
            }

            // Arahkan berdasarkan role
            switch (Auth::user()->role) {
                case 'user':
                    return redirect()->route('dashboard.user.index');
                case 'admin':
                    return redirect()->route('dashboard.admin.index');
                case 'management':
                    return redirect()->route('dashboard.management.index');
                case 'dev':
                    return redirect()->route('dashboard.dev.index');
                default:
                    Auth::logout();
                    return redirect('/')->withErrors('Periksa Akun Kembali');
            }
        } else {
            return redirect('/')->withErrors('Email atau Password yang Anda masukan salah!')->withInput();
        }
    }
    public function userList()
    {
        
        $users = User::whereIn('role', ['admin', 'user'])->get();
        return view('dashboard.admin.actuser.tableactuser', [
            'title' => 'Management User',
            'users' => $users
        ]);
    }

    public function editUser($id)
    {
        $users = User::findOrFail($id);
        return view('dashboard.admin.actuser.edituser', [
            'title' => 'Edit User',
            'users' => $users
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'phone' => "nullable|numeric|digits_between:10,13|unique:users,phone,{$id}",
            'company' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin,management,dev',
            'is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }


    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
