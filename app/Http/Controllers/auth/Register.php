<?php

namespace App\Http\Controllers\auth;

use App\Models\role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Untuk fungsi Hash::make
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;


// class Register extends Controller
//{
    // public function create()
    // {
    //     $roles = role::select('role_id', 'role_name')->get();
    //     return view('auth.index-register', compact('roles'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string',
    //         'nip' => 'required|string|unique:users,nip',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:6',
    //         'role_id' => 'required',
    //     ]);
    
        // Simpan ke DB
        // $user = \App\Models\User::create([
        //     'nama' => $request->nama,
        //     'nip' => $request->nip,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'role_id' => $request->role_id,
        //     'status' => 'pending', // akun belum aktif
        // ]);
    
        // Buat link WhatsApp otomatis
        // $adminWa = '6281234567890'; // Ganti dengan nomor admin kamu
        // $pesan = "Kepada Admin SIJAMU FKIP,%0AMohon kesediaannya untuk mengaktifkan akun pengguna berikut:%0ANama: {$user->nama}%0ANIP: {$user->nip}%0AEmail: {$user->email}";
        // $linkWa = "https://wa.me/{$adminWa}?text=" . $pesan;

        // return redirect()->route('auth.register')->with('whatsappLink', $linkWa);

        
                // Kirim email verifikasi
        //  event(new Registered($user));
        //  return redirect()->route('auth.login')->with('status', 'Silakan login setelah verifikasi email!');

        //  return redirect('/email/verify')->with('message', 'Silakan cek email Anda untuk verifikasi.');
        // return redirect()->route('auth.login')->with('Selesai', 'Akun berhasil dibuat.');

    //}
//}
