<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login', [
            'title' => 'Login',
        ]); 
    }

    public function authenticate(Request $request){
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
        $credential=[
            'email'=>$request->email,
            'password'=>$request->password
        ];
        if (Auth::attempt($credential)) {
            if (Auth::user()->role == 'user') {
                return redirect()->route('dashboard.user.index');
            } else if((Auth::user()->role == 'admin')){
                return redirect()->route('dashboard.admin.index');
            }
             else {
                Auth::logout();
                return redirect('/')->withErrors('Periksa Akun Kembali');
            }
        } else {
            return redirect('/')->withErrors('Username atau Password yang Anda masukan salah!')->withInput();
        }
    }
}
