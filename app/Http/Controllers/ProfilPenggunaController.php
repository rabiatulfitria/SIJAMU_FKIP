<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilPenggunaController extends Controller
{
    public function profilpengguna()
    {
        $user = Auth::user();

        $roles = Role::all();

        return view('User.admin.profilpengguna', [
            'oldData' => $user,
            'roles' => $roles,
            'role_id' => $user->role_id,
        ]);
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'nip' => 'required',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $user->nip = $request->nip;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('BerandaSIJAMUFKIP')->with('success', 'Profil berhasil diperbarui.');
    }

    public function pengaturan()
    {
        return view('User.admin.pengaturan'); // halaman daftar menu pengaturan (misal: ubah password, privasi)
    }

    public function formUbahPassword()
    {
        return view('User.admin.pengaturan_ubahpassword');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
        ]);

        // Cek apakah password baru dan konfirmasi cocok 
        if ($request->new_password !== $request->confirm_password) {
            return back()->with('error', 'Password baru dan konfirmasi password baru tidak cocok.');
        }
        
        // Cek apakah password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('BerandaSIJAMUFKIP')->with('success', 'Password Berhasil Diubah');
    }

    public function resetPasswordToNIP(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Password akan di-set ulang menjadi NIP
        $user->password = Hash::make($user->nip);
        $user->save();

        return redirect()->route('BerandaSIJAMUFKIP')->with('success', 'Password berhasil di reset');
    }
}
