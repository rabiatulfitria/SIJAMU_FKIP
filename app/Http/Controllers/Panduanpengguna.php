<?php

namespace App\Http\Controllers;

use App\Models\panduan_pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Panduanpengguna extends Controller
{
    public function Unduhpanduan()
    {
        $pdfPath = null;
    if (Storage::disk('public')->exists('file_panduanpengguna/panduanpengguna.pdf')) {
        $pdfPath = asset('storage/file_panduanpengguna/panduanpengguna.pdf');
    }

    return view('User.menulain_halamanLogin.Panduanpengguna', compact('pdfPath'));
    }

    public function index()
    {
        $panduan = panduan_pengguna::all();
        return view('User.admin.panduan.index-panduan', compact('panduan'));
    }

    // public function unggahfile(Request $request)
    // {
    //     $request->validate([
    //         'nama_file' => 'required|string',
    //         'tahun' => 'required|string',
    //         'pdf' => 'nullable||file|mimes:pdf|max:5120', //name input form
    //     ]);
 
    //     $file = $request->file('pdf'); 
    //     $path = $file->storeAs('file_panduanpengguna', $file->getClientOriginalName(), 'public');

    //     panduan_pengguna::create([
    //         'nama_file' => $request->input('nama_file'),
    //         'tahun' => $request->input('tahun'),
    //         'path' => $path,
    //     ]);

    //     return back()->with('success', 'File berhasil diunggah.');
    // }

    public function unggahfile($id)
    {
        $panduan = panduan_pengguna::findOrFail($id);
        return view('User.admin.panduan.edit-panduan', compact('panduan'));
    }

    public function updatefile(Request $request, $id)
    {
        $panduan = panduan_pengguna::findOrFail($id);

        $request->validate([
            'nama_file' => 'required|string',
            'tahun' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Cek apakah ada file yang diunggah
        if ($request->hasFile('pdf')) {
            // Hapus file lama jika ada
            if ($panduan->path && Storage::disk('public')->exists($panduan->path)) {
                Storage::disk('public')->delete($panduan->path);
            }

            // Simpan file baru
            $file = $request->file('pdf');
            $path = $file->storeAs('file_panduanpengguna', $file->getClientOriginalName(), 'public');
            $panduan->path = $path;
        }

        $panduan->nama_file = $request->input('nama_file');
        $panduan->tahun = $request->input('tahun');
        $panduan->save();

        return redirect()->route('admin.panduan.index')->with('success', 'Data berhasil diperbarui.');
    }
}
