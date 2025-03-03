<?php

namespace App\Http\Controllers;

use App\Models\Pengendalian;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class pengendalianController extends Controller
{
    public function index()
    {
        // Ambil data pengendalian beserta function prodi terkait menggunakan Eloquent ORM
        $pengendalian = Pengendalian::with('prodi')
            ->select('id_pengendalian', 'nama_dokumen', 'tahun', 'id_prodi', 'file_rtm', 'file_rtl')
            ->get();

        return view('User.admin.Pengendalian.index_pengendalian', compact('pengendalian'));
    }

    public function create()
    {
        $prodi= Prodi::all();
        return view('User.admin.Pengendalian.tambah_pengendalian', compact('prodi'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'nama_dokumen' => 'required|string',
                'tahun' => 'required|string',
                'file_rtm' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120',
                'file_rtl' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
            ]);

            $data = $request->all();

            // Proses upload file 
            if ($request->hasFile('file_rtm')) {
                $rtmPath = $request->file('file_rtm')->store('laporan_rtm', 'public'); // simpan di storage/app/public/laporan_rtm
                $data['file_rtm'] = $rtmPath;
            }

            // Proses upload file
            if ($request->hasFile('file_rtl')) {
                $rtlPath = $request->file('file_rtl')->store('laporan_rtl', 'public'); // simpan di storage/app/public/laporan_rtl
                $data['file_rtl'] = $rtlPath;
            }

            // Simpan data ke database
            Pengendalian::create([
                'nama_dokumen' => $data['nama_dokumen'],
                'tahun' => $data['tahun'],
                // 'file_rtm' => $data['file_rtm'] ?? null,
                // 'file_rtl' => $data['file_rtl'] ?? null,
                
                'file_rtm' => $data['file_rtm'],
                'file_rtl' => $data['file_rtl'],
                'id_prodi' => $data['id_prodi'],
            ]);


            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data pengendalian dan dokumen berhasil ditambahkan.');
            return redirect()->route('pengendalian');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenpengendalian($id_pengendalian, $jenis_file)
    {
        try {
            // Cari data pengendalian berdasarkan ID
            $pengendalian = Pengendalian::findOrFail($id_pengendalian);

            // Pilih file berdasarkan jenis_file
            $filePath = null;
            if ($jenis_file === 'rtm') {
                $filePath = $pengendalian->file_rtm;
            } elseif ($jenis_file === 'rtl') {
                $filePath = $pengendalian->file_rtl;
            }

            // Jika filePath kosong, abort dengan pesan error
            if (!$filePath) {
                abort(404, 'File tidak ditemukan.');
            }

            // Periksa apakah file ada di storage lokal
            if (Storage::disk('public')->exists($filePath)) {
                // Kirim file untuk ditampilkan di browser
                return response()->file(storage_path('app/public/' . $filePath));
            } else {
                abort(404, 'File tidak ditemukan di penyimpanan.');
            }
        } catch (\Exception $e) {
            // Menampilkan error jika terjadi kesalahan
            abort(500, 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(String $id_pengendalian)
    {
        try {
            // Cari data pengendalian berdasarkan ID
            $pengendalian = Pengendalian::with('prodi') // Mengambil relasi dengan tabel prodi jika ada
                ->findOrFail($id_pengendalian);

            // Kembalikan data ke view edit_pengendalian
            return view('User.admin.Pengendalian.edit_pengendalian', [
                'oldData' => $pengendalian, // Data pengendalian
            ]);
        } catch (\Exception $e) {
            // Menangkap error jika terjadi masalah
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id_pengendalian)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nama_dokumen' => 'required|string',
                'tahun' => 'required|string',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'file_rtm' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120', 
                'file_rtl' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120', 
            ]);

            // Ambil data pengendalian berdasarkan ID
            $pengendalian = Pengendalian::findOrFail($id_pengendalian);

            // Proses upload file baru jika ada
            if ($request->hasFile('file_rtm')) {
                // Hapus file lama jika ada
                if ($pengendalian->file_rtm && Storage::exists('public/' . $pengendalian->file_rtm)) {
                    Storage::delete('public/' . $pengendalian->file_rtm);
                }
                // Simpan file baru
                $fileRTMPath = $request->file('file_rtm')->store('laporan_rtm', 'public');
                $pengendalian->file_rtm = $fileRTMPath;
            }

            if ($request->hasFile('file_rtl')) {
                // Hapus file lama jika ada
                if ($pengendalian->file_rtl && Storage::exists('public/' . $pengendalian->file_rtl)) {
                    Storage::delete('public/' . $pengendalian->file_rtl);
                }
                // Simpan file baru
                $fileRTLPath = $request->file('file_rtl')->store('laporan_rtl', 'public');
                $pengendalian->file_rtl = $fileRTLPath;
            }

            // Perbarui data lainnya
            $pengendalian->update([
                'nama_dokumen' => $validatedData['nama_dokumen'],
                'tahun' => $validatedData['tahun'],
                'id_prodi' => $validatedData['id_prodi'],
            ]);

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data pengendalian berhasil diperbarui.');
            return redirect()->route('pengendalian'); // Ganti dengan route yang sesuai
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_pengendalian)
    {
        try {
            // Cari data pengendalian berdasarkan ID
            $pengendalian = Pengendalian::findOrFail($id_pengendalian);

            // Hapus file RTM jika ada
            if ($pengendalian->file_rtm && Storage::exists('public/' . $pengendalian->file_rtm)) {
                Storage::delete('public/' . $pengendalian->file_rtm);
            }

            // Hapus file RTL jika ada
            if ($pengendalian->file_rtl && Storage::exists('public/' . $pengendalian->file_rtl)) {
                Storage::delete('public/' . $pengendalian->file_rtl);
            }

            // Hapus data dari database
            $pengendalian->delete();

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data pengendalian berhasil dihapus.');
            return redirect()->route('pengendalian'); // Ganti dengan route yang sesuai
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
