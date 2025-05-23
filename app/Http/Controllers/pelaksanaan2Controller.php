<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\pelaksanaan_fakultas;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class pelaksanaan2Controller extends Controller
{
    public function index()
    {
        // Mengambil data pelaksanaan_fakultas dengan relasi ke kategori
        $plks_fklts = pelaksanaan_fakultas::with('kategori')
            ->whereHas('kategori', function ($query) {
                $query->whereIn('nama_kategori', [
                    'Renstra Fakultas',
                    'Laporan Kinerja Fakultas',
                ]);
            })
            ->get();

        // Memisahkan data berdasarkan kategori
        $renstraFakultas = $plks_fklts->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Renstra Fakultas';
        });

        $laporanKinerjaFakultas = $plks_fklts->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Laporan Kinerja Fakultas';
        });

        // Mengembalikan view dengan data yang telah dikelompokkan
        return view('User.admin.Pelaksanaan.index_fakultas', compact(
            'renstraFakultas',
            'laporanKinerjaFakultas',
        ));
    }

    public function create(Request $request)
    {
        $menu = $request->query('menu'); // ambil dari URL

        // Mengirim data ke view
        return view('User.admin.Pelaksanaan.tambah_pelaksanaan_fakultas', [
            'menu' => $menu,
            'kategori' => kategori::all(),
        ]);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'namafile' => 'required|string|max:255',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'periode_tahunakademik' => 'required|string|max:255',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480',
            ]);

            $data = $request->all();

            // Proses upload file 
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName(); // Mendapatkan nama asli file
                $path = $file->storeAs('pelaksanaan/fakultas', $fileName, 'public'); // Simpan file dengan nama asli
                $data['file'] = $path;
            }

            // Simpan data ke database
            pelaksanaan_fakultas::create([
                'namafile' => $data['namafile'],
                'id_kategori' => $data['id_kategori'],
                'periode_tahunakademik' => $data['periode_tahunakademik'],
                'file' => $data['file'],
            ]);

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data dan dokumen berhasil ditambahkan.');
            return redirect()->route('pelaksanaan.fakultas');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenPlksFakultas($id_plks_fklts, $namafile)
    {
        // Cari dokumen berdasarkan ID dan nama file
        $dokumenplks_fklts = pelaksanaan_fakultas::where('id_plks_fklts', $id_plks_fklts)
            ->where('namafile', $namafile)
            ->firstOrFail();

        // Tentukan path file
        $filePath = storage_path('app/public/' . $dokumenplks_fklts->file);

        // Cek apakah file ada di penyimpanan
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
        abort_if(!isset($mimeTypes[$fileExtension]), 403, 'Format file tidak didukung.');

        // Jika file berupa DOC, DOCX, XLS, XLSX, langsung di-download
        $forceDownloadExtensions = ['doc', 'docx', 'xls', 'xlsx'];

        if (in_array($fileExtension, $forceDownloadExtensions)) {
            return response()->download($filePath, $dokumenplks_fklts->namafile . '.' . $fileExtension, [
                'Content-Type'              => $mimeTypes[$fileExtension],
                'Content-Disposition'       => 'attachment; filename="' . $dokumenplks_fklts->namafile . '.' . $fileExtension . '"',
                'X-Content-Type-Options'    => 'nosniff',
                'Cache-Control'             => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'                    => 'no-cache',
                'Expires'                   => '0',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length'            => filesize($filePath)
            ]);
        }

        // Jika bukan dokumen office, tampilkan langsung di browser
        return response()->file($filePath, [
            'Content-Type' => $mimeTypes[$fileExtension],
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function edit(Request $request, string  $id_plks_fklts)
    {
        $menu = $request->query('menu');

        // Ambil data pelaksanaan_fakultas yang ingin diedit dengan relasi terkait
        $plks_fklts = pelaksanaan_fakultas::with(['kategori'])
            ->where('id_plks_fklts', $id_plks_fklts)
            ->firstOrFail();

        // Ambil daftar kategori
        $kategori = kategori::select('id_kategori', 'nama_kategori')->get();

        return view('User.admin.Pelaksanaan.edit_pelaksanaan_fakultas', [
            'oldData' => $plks_fklts,
            'kategori' => $kategori,
            'menu' => $menu,
        ]);
    }

    public function update(Request $request, $id_plks_fklts)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'namafile' => 'required|string|max:255',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'periode_tahunakademik' => 'required|string|max:255',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480' // Maksimum 20MB
            ]);
    
            // Ambil data berdasarkan ID
            $plks_fklts = pelaksanaan_fakultas::findOrFail($id_plks_fklts);
    
            // Persiapkan data untuk diupdate
            $updateData = [
                'namafile' => $validatedData['namafile'],
                'id_kategori' => $validatedData['id_kategori'],
                'periode_tahunakademik' => $validatedData['periode_tahunakademik'],
            ];
    
            // Proses upload file baru jika ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
    
                // Buat nama file unik agar tidak ditimpa oleh cache browser
                $newFileName = $originalName;
                $counter = 1;
                while (Storage::exists('public/pelaksanaan/fakultas/' . $newFileName . '.' . $extension)) {
                    $newFileName = $originalName . '_' . $counter;
                    $counter++;
                }
    
                // Hapus file lama jika ada
                if ($plks_fklts->file && Storage::exists('public/' . $plks_fklts->file)) {
                    Storage::delete('public/' . $plks_fklts->file);
                }
    
                // Simpan file baru dengan nama unik
                $filePlksFakultasPath = $file->storeAs('pelaksanaan/fakultas', $newFileName . '.' . $extension, 'public');
    
                // Tambahkan path file baru ke data yang akan diperbarui
                $updateData['file'] = $filePlksFakultasPath;
            }
    
            // Update semua data sekaligus
            $plks_fklts->update($updateData);
            DB::commit();
            
            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data berhasil diperbarui.');
            return redirect()->route('pelaksanaan.fakultas');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_plks_fklts)
    {
        try {
            // Ambil data dokumen berdasarkan ID menggunakan Eloquent
            $dokumen = pelaksanaan_fakultas::findOrFail($id_plks_fklts);

            // Hapus file dari storage jika file ada
            if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                Storage::delete('public/' . $dokumen->file);
            }

            // Hapus data dari database
            $dokumen->delete();

            // Berikan notifikasi sukses
            Alert::success('Selesai', 'Dokumen berhasil dihapus.');
            return redirect()->route('pelaksanaan.fakultas');
        } catch (ModelNotFoundException $e) {
            // Jika dokumen tidak ditemukan
            Alert::error('error', 'Dokumen tidak ditemukan.');
            return redirect()->route('pelaksanaan.fakultas');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan lain
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
