<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
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
                'regex:/[a-z]/',    // huruf kecil
                'regex:/[A-Z]/',    // huruf besar
                'regex:/[0-9]/',    // angka
            ],
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => '6LcubmMrAAAAAHfIUTLFlV-lTgpURvmuknwWDTn4',
                    'response' => $value,
                ]);
                if (!$response->json('success')) {
                    $fail('Captcha tidak valid.');
                }
            }],
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
            'g-recaptcha-response.required' => 'Captcha wajib diisi.',
        ]);


        // format nomor Indonesia
        $phone = preg_replace('/[\s\-]/', '', $request->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Simpan ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('register')->with('success', 'Berhasil mendaftarkan akun! Silakan login.');
    }
}
