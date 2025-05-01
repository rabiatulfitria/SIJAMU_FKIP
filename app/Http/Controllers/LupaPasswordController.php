<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;


class LupaPasswordController extends Controller
{
    public function index()
    {
        return view('auth.lupa-password');
    }

    public function cariAkun(Request $request)
    {
        $nip = $request->nip;
        $email = $request->email;

        $user = User::where('nip', $nip)
            ->where('email', $email)
            ->first();

        if (!$user) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Simpan (ambil dari request dan disimpan sementara di session)
        session([
            'nip' => $nip,
            'email' => $email,
            'id' => $user->id,
        ]);

        return redirect()->route('password-baru.form', ['id' => $user->id]);
    }

    public function passwordbaruForm($id)
    {
        $user = User::findOrFail($id);
        return view('auth.password-baru', compact('user'));
    }

    public function passwordBaru(Request $request, $id)
    {
        // dd($request->all());
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        // hapus session
        session()->forget(['nip', 'email', 'id']);

        Alert::success('Selesai', 'Password berhasil diubah. Silakan login kembali.');
        return redirect()->route('auth.login');
    }
}
