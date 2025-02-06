<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Peningkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class peningkatanController extends Controller
{
    public function index()
    {
        // Ambil data peningkatan beserta prodi terkait menggunakan Eloquent ORM
        $peningkatan = Peningkatan::with('prodi')
            ->select('id_peningkatan', 'nama_dokumen', 'bidang_standar', 'tanggal_penetapan_baru', 'id_prodi', 'file_peningkatan')
            ->get();

        // Kembalikan data ke view
        return view('User.admin.Peningkatan.index_peningkatan', compact('peningkatan'));
    }

    public function create()
    {
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();
        return view('User.admin.Peningkatan.tambah_dokumen_peningkatan', compact('prodi'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'nama_dokumen' => 'required|string',
                'bidang_standar' => 'required|string',
                'tanggal_penetapan_baru' => 'required|string',
                'file_peningkatan' => 'file|mimes:pdf,doc,docx,xlsx,url|max:5120',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
            ]);

            $data = $request->all();

            // Proses upload file 
            if ($request->hasFile('file_peningkatan')) {
                $file = $request->file('file_peningkatan');
                $fileName = $file->getClientOriginalName(); // Mendapatkan nama asli file
                $path = $file->storeAs('peningkatan', $fileName, 'public'); // Simpan file dengan nama asli
                $data['file_peningkatan'] = $path;
            }
            
            // Simpan data ke database
            Peningkatan::create([
                'nama_dokumen' => $data['nama_dokumen'],
                'bidang_standar' => $data['bidang_standar'],
                'tanggal_penetapan_baru' => $data['tanggal_penetapan_baru'],
                'file_peningkatan' => $data['file_peningkatan'],
                'id_prodi' => $data['id_prodi'],
            ]);


            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data peningkatan dan dokumen berhasil ditambahkan.');
            return redirect()->route('peningkatan');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenpeningkatan($id_peningkatan, $jenis_file)
    {
        try {
            // Cari data peningkatan berdasarkan ID
            $peningkatan = Peningkatan::findOrFail($id_peningkatan);

            // Ambil path file dari model
            $filePath = $peningkatan->file_peningkatan;


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

    public function edit(String $id_peningkatan)
    {
        try {
            // Cari data peningkatan berdasarkan ID
            $peningkatan = Peningkatan::with('prodi') // Mengambil relasi dengan tabel prodi jika ada
                ->findOrFail($id_peningkatan);
                
            // Ambil daftar program studi
            $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();


            // Kembalikan data ke view edit_peningkatan
            return view('User.admin.Peningkatan.edit_peningkatan', [
                'oldData' => $peningkatan, // Data peningkatan
                'prodi' => $prodi,
            ]);
        } catch (\Exception $e) {
            // Menangkap error jika terjadi masalah
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id_peningkatan)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nama_dokumen' => 'required|string',
                'bidang_standar' => 'required|string',
                'tanggal_penetapan_baru' => 'required|string',
                'file_peningkatan' => 'file|mimes:pdf,doc,docx,xlsx|max:5120',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
            ]);

            // Ambil data peningkatan berdasarkan ID
            $peningkatan = Peningkatan::findOrFail($id_peningkatan);

            // Proses upload file baru jika ada
            if ($request->hasFile('file_peningkatan')) {
                // Hapus file lama jika ada
                if ($peningkatan->file_peningkatan && Storage::exists('public/' . $peningkatan->file_peningkatan)) {
                    Storage::delete('public/' . $peningkatan->file_penigkatan);
                }
                // Simpan file baru
                $filePeningkatanPath = $request->file('file_peningkatan')->store('peningkatan', 'public');
                $peningkatan->file_peningkatan = $filePeningkatanPath;
            }

            // Perbarui data lainnya
            $peningkatan->update([
                'nama_dokumen' => $validatedData['nama_dokumen'],
                'bidang_standar' => $validatedData['bidang_standar'],
                'tanggal_penetapan_baru' => $validatedData['tanggal_penetapan_baru'],
                'file_peningkatan' => $validatedData['file_peningkatan'],
                'id_prodi' => $validatedData['id_prodi'],
            ]);

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data peningkatan berhasil diperbarui.');
            return redirect()->route('peningkatan'); // Ganti dengan route yang sesuai
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id_peningkatan)
    {
        try {
            // Cari data peningkatan berdasarkan ID
            $peningkatan = Peningkatan::findOrFail($id_peningkatan);

            // Hapus file peningkatan jika ada
            if ($peningkatan->file_peningkatan && Storage::exists('public/' . $peningkatan->file_peningkatan)) {
                Storage::delete('public/' . $peningkatan->file_peningkatan);
            }

            // Hapus data dari database
            $peningkatan->delete();

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data peningkatan berhasil dihapus.');
            return redirect()->route('peningkatan'); // Ganti dengan route yang sesuai
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
