<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Pastikan model User diimport
use Illuminate\Support\Facades\Hash; // Untuk fungsi Hash::make

class Register extends Controller
{
    public function index()
    {
        return view('auth.index-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'level' => 'required|string',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // buat hashing password
            'level' => $request->level,
        ]);

        return redirect()->route('auth.login')->with('Selesai', 'Akun berhasil dibuat.');
    }
}
