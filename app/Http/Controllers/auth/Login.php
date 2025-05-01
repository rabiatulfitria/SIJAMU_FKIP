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

        // Ambil user dari database
        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user) {
            // // Cek status dulu
            // if ($user->status !== 'active') {
            //     $linkWa = "https://wa.me/6281234567890?text=" . urlencode(
            //         "Kepada Admin SIJAMU FKIP, mohon kesediaannya untuk mengaktifkan akun pengguna berikut:\n" .
            //         "Nama : ..." .
            //         "NIP : ..." .
            //         "Email : ..."
            //     );
        
            //     Alert::html('Akun Belum Aktif', 
            //         'Akun Anda belum diaktifkan oleh Admin. Silakan <a href="' . $linkWa . '" target="_blank" style="color:#007bff;">Hubungi Admin via WhatsApp</a>.',
            //         'warning'
            //     )->persistent()->showConfirmButton('<i class="fa fa-times"></i> Tutup', '#d33');
        
            //     return redirect()->back();
            // }

            // Cek password
            if (Hash::check($request->password, $user->password)) {
                Auth::loginUsingId($user->id);
                Alert::success('Selesai', 'Login berhasil.');
                return redirect()->route('BerandaSIJAMUFKIP');
            } else {
                Alert::error('Gagal', 'Password salah!');
                return redirect()->back();
            }
        } else {
            Alert::error('Gagal', 'Email tidak ditemukan!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        // Logout user
        Auth::logout();

        // Redirect ke halaman login dengan pesan sukses
        // Alert::success('Selesai', 'Logout berhasil.');
        return redirect()->route('auth.login');
    }
}
