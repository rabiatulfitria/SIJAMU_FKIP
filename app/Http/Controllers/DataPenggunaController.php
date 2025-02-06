<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class DataPenggunaController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('User.admin.datapengguna.index', compact('users'));
    }

    public function create()
    {
        $roles = role::select('role_id', 'role_name')->get();
        return view('User.admin.datapengguna.tambah', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password
            'role_id' => $request->role_id,
        ]);

        Alert::success('Selesai', 'Data Pengguna berhasil ditambahkan.');
        return redirect()->route('DataPengguna');
    }

    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        $roles = role::select('role_id', 'role_name')->get();
        
        return view('User.admin.datapengguna.edit', [
            'oldData' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6', // Tidak wajib diisi
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        // Jika password diisi, maka di-hash sebelum diupdate
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        Alert::success('Selesai', 'Data Pengguna berhasil diperbarui.');
        return redirect()->route('DataPengguna');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        Alert::success('Selesai', 'Data Pengguna berhasil dihapus.');
        return redirect()->route('DataPengguna');
    }
}
