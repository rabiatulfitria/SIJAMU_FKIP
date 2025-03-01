<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;


class Login extends Controller
{
    public function index()
    {
        return view('auth.index-login');
    }

    // Fungsi login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi!',
            'email.email' => 'Format email tidak valid!',
            'password.required' => 'Password harus diisi!',
        ]);

        // Ambil user dari database menggunakan query builder
        $user = DB::table('users')->where('email', $request->email)->first();

        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            // Login user menggunakan Auth
            Auth::loginUsingId($user->id);

            // Redirect ke halaman sijamufip
            Alert::success('Selesai', 'Login berhasil.');
            return redirect()->route('BerandaSIJAMUFIP');
        } else {
            // Jika email atau password salah, kembali ke halaman login dengan pesan error
            Alert::error('Gagal', 'Email atau password salah!.');
            return redirect()->back();
        }

    }

    // Fungsi logout
    public function logout()
    {
        // Logout user
        Auth::logout();

        // Redirect ke halaman login dengan pesan sukses
        Alert::success('Selesai', 'Logout berhasil.');
        return redirect()->route('auth.login');
    }


}
