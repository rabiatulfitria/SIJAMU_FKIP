<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User diimport
use App\Models\role;
use Illuminate\Support\Facades\Hash; // Untuk fungsi Hash::make

class Register extends Controller
{
    // public function index()
    // {
    //     $users = User::with('role')->get();
    //     $role = role::select('role_id', 'role_name')->get(); // Ambil daftar role
    
    //     return view('auth.index-register', compact('users', 'role'));    }

    public function create()
    {
        $roles = role::select('role_id', 'role_name')->get();
        return view('auth.index-register', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // buat hashing password
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('auth.login')->with('Selesai', 'Akun berhasil dibuat.');
    }
}
