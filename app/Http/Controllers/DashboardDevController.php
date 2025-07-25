<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardDevController extends Controller
{
    public function index()
    {
        return view('dashboard.dev.index', [
            'title' => 'Dewa Dev',
        ]);
    }

    public function allUser()
    {
        $users = User::all();

        return view('dashboard.dev.alluser.tableuser', [
            'title' => 'Management User',
            'users' => $users
        ]);
    }
    public function editUser($id)
    {
        $users = User::findOrFail($id);
        return view('dashboard.dev.alluser.edituser', [
            'title' => 'Edit User By Dev',
            'users' => $users
        ]);
    }
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard.dev.alluser')->with('success', 'User berhasil dihapus.');
    }


    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'phone' => "nullable|numeric|digits_between:10,13|unique:users,phone,{$id}",
            'company' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin,management,dev,viewer',
            'is_active' => 'required|boolean',
            'password' => [
                'nullable',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',   // huruf kecil
                'regex:/[A-Z]/',   // huruf besar
                'regex:/[0-9]/',   // angka
            ],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus terdiri dari huruf besar, huruf kecil, dan angka.',
        ]);

        $user = User::findOrFail($id);

        // Ambil field biasa
        $data = $request->only([
            'name',
            'email',
            'phone',
            'company',
            'role',
            'is_active',
        ]);

        // Cek jika password diisi, maka hash dan masukkan
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($data);

        return redirect()
            ->route('dashboard.dev.edit', ['id' => $user->id])
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function homeaddUser()
    {
        return view('dashboard.dev.alluser.adduser', [
            'title' => 'Management User',
        ]);
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'company' => ['nullable', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'numeric', 'digits_between:10,13', 'unique:users,phone'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'phone.digits_between' => 'Nomor WhatsApp harus diisi dengan benar.',
            'phone.unique' => 'Nomor WhatsApp sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus terdiri dari huruf besar, huruf kecil, dan angka.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        $phone = preg_replace('/[\s\-]/', '', $request->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);
        return redirect()->route('dashboard.dev.add')->with('success', 'Berhasil mendaftarkan akun! Silakan login.');
    }
}
