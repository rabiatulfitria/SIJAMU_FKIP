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
        return view('User.admin.Peningkatan.tambah_peningkatan', compact('prodi'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_dokumen' => 'required|string',
                'bidang_standar' => 'required|string',
                'tanggal_penetapan_baru' => 'required|string',
                'file_peningkatan' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120',
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

    public function lihatdokumenpeningkatan($id_peningkatan, $nama_dokumen)
    {
        // Ambil data file berdasarkan id_peningkatan dan nama_dokumen
        $peningkatan = Peningkatan::where('id_peningkatan', $id_peningkatan)
            ->where('nama_dokumen', $nama_dokumen)
            ->firstOrFail();

        $filePath = storage_path('app/public/' . $peningkatan->file_peningkatan);

        // Cek apakah file ada
        abort_if(!file_exists($filePath), 404, 'File tidak ditemukan.');

        // Tentukan ekstensi file
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Daftar MIME Types yang diizinkan
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        ];

        // Cek apakah ekstensi file didukung
        if (!isset($mimeTypes[$fileExtension])) {
            abort(403, 'Format file tidak didukung.');
        }

        // Jika file berupa DOC, DOCX, XLS, XLSX, langsung di-download
        $forceDownloadExtensions = ['doc', 'docx', 'xls', 'xlsx'];

        if (in_array($fileExtension, $forceDownloadExtensions)) {
            return response()->streamDownload(function () use ($filePath) {
                $handle = fopen($filePath, 'rb');
                fpassthru($handle);
                fclose($handle);
            }, $peningkatan->nama_dokumen . '.' . $fileExtension, [
                'Content-Type'              => $mimeTypes[$fileExtension],
                'Content-Disposition'       => 'attachment; filename="' . $peningkatan->nama_dokumen . '.' . $fileExtension . '"',
                'X-Content-Type-Options'    => 'nosniff',
                'Cache-Control'             => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'                    => 'no-cache',
                'Expires'                   => '0',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length'            => filesize($filePath)
            ]);
        }

        // Untuk PDF & gambar, tampilkan langsung di browser
        return response()->file($filePath, [
            'Content-Type' => $mimeTypes[$fileExtension],
            'Content-Disposition' => 'inline; filename="' . $peningkatan->nama_dokumen . '.' . $fileExtension . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function edit(String $id_peningkatan)
    {
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
    }

    public function update(Request $request, $id_peningkatan)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nama_dokumen' => 'required|string',
                'bidang_standar' => 'required|string',
                'tanggal_penetapan_baru' => 'required|string',
                'file_peningkatan' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480', // Maksimum 20MB
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
            ]);

            // Ambil data peningkatan berdasarkan ID
            $peningkatan = Peningkatan::findOrFail($id_peningkatan);

            // Persiapkan data untuk diupdate
            $updateData = [
                'nama_dokumen' => $validatedData['nama_dokumen'],
                'bidang_standar' => $validatedData['bidang_standar'],
                'tanggal_penetapan_baru' => $validatedData['tanggal_penetapan_baru'],
                'id_prodi' => $validatedData['id_prodi'],
            ];

            // Proses upload file baru jika ada
            if ($request->hasFile('file_peningkatan')) {
                $file = $request->file('file_peningkatan');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                // Buat nama file unik agar tidak ditimpa oleh cache browser
                $newFileName = $originalName;
                $counter = 1;
                while (Storage::exists('public/peningkatan/' . $newFileName . '.' . $extension)) {
                    $newFileName = $originalName . '_' . $counter;
                    $counter++;
                }

                // Hapus file lama jika ada
                if ($peningkatan->file_peningkatan && Storage::exists('public/' . $peningkatan->file_peningkatan)) {
                    Storage::delete('public/' . $peningkatan->file_peningkatan);
                }

                // Simpan file baru dengan nama unik
                $filePeningkatanPath = $file->storeAs('peningkatan', $newFileName . '.' . $extension, 'public');

                // Tambahkan path file baru ke data yang akan diperbarui
                $updateData['file_peningkatan'] = $filePeningkatanPath;
            }

            // Update semua data sekaligus
            $peningkatan->update($updateData);

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data peningkatan berhasil diperbarui.');
            return redirect()->route('peningkatan');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
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
