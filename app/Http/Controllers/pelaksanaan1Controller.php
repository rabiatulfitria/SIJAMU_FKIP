<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\kategori;
use Illuminate\Http\Request;
use App\Models\pelaksanaan_prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class pelaksanaan1Controller extends Controller
{
    public function index()
    {
        // Mengambil data pelaksanaan_prodi dengan relasi ke prodi dan kategori
        $plks_prodi = pelaksanaan_prodi::with('prodi', 'kategori')
            ->whereHas('kategori', function ($query) {
                $query->whereIn('nama_kategori', [
                    'Renstra Program Studi',
                    'Laporan Kinerja Program Studi',
                    'Dokumen Kurikulum',
                    'Rencana Pembelajaran Semester (RPS)',
                    'Dokumen Monitoring dan Evaluasi Kegiatan Program MBKM',
                    'Capaian Pembelajaran Lulusan (CPL)',
                    'Panduan RPS',
                    'Panduan Mutu Soal',
                    'Panduan Kisi Kisi Soal',
                    'Formulir Kepuasan Mahasiswa',
                    'Dokumen Monitoring dan Evaluasi Ketercapaian Standar Layanan Kemahasiswaan',
                ]);
            })
            ->get();

        // Memisahkan data berdasarkan kategori
        $renstraProgramStudi = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Renstra Program Studi';
        });

        $laporanKinerjaProgramStudi = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Laporan Kinerja Program Studi';
        });

        $dokumenKurikulum = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Dokumen Kurikulum';
        });

        $rps = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Rencana Pembelajaran Semester (RPS)';
        });

        $monitoringMbkm = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Dokumen Monitoring dan Evaluasi Kegiatan Program MBKM';
        });

        $cpl = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Capaian Pembelajaran Lulusan (CPL)';
        });

        $panduanRps = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Panduan RPS';
        });

        $panduanMutuSoal = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Panduan Mutu Soal';
        });

        $panduanKisiKisi = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Panduan Kisi Kisi Soal';
        });

        $formulirKepuasan = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Formulir Kepuasan Mahasiswa';
        });

        $monitoringKemahasiswaan = $plks_prodi->filter(function ($item) {
            return $item->kategori->nama_kategori === 'Dokumen Monitoring dan Evaluasi Ketercapaian Standar Layanan Kemahasiswaan';
        });

        // Mengembalikan view dengan data yang telah dikelompokkan
        return view('User.admin.Pelaksanaan.index_prodi', compact(
            'renstraProgramStudi',
            'laporanKinerjaProgramStudi',
            'dokumenKurikulum',
            'rps',
            'monitoringMbkm',
            'cpl',
            'panduanRps',
            'panduanMutuSoal',
            'panduanKisiKisi',
            'formulirKepuasan',
            'monitoringKemahasiswaan'
        ));
    }

    public function create()
    {
        // Mengambil data prodi dan kategori menggunakan model
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();
        $kategori = kategori::select('id_kategori', 'nama_kategori')->get();

        // Mengirim data ke view
        return view('User.admin.Pelaksanaan.tambah_dokumen_pelaksanaan_prodi', compact('prodi', 'kategori'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'namafile' => 'required|string|max:255',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'periode_tahunakademik' => 'required|string|max:255',
                'file' => 'file|mimes:pdf,doc,docx,xlsx|max:5120', // Maksimum 5120 KB (5 MB)
            ]);

            $data = $request->all();

            // Proses upload file 
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName(); // Mendapatkan nama asli file
                $path = $file->storeAs('pelaksanaan/prodi', $fileName, 'public'); // Simpan file dengan nama asli
                $data['file'] = $path;
            }

            // Simpan data ke database
            pelaksanaan_prodi::create([
                'namafile' => $data['namafile'],
                'id_kategori' => $data['id_kategori'],
                'id_prodi' => $data['id_prodi'],
                'periode_tahunakademik' => $data['periode_tahunakademik'],
                'file' => $data['file'],
            ]);


            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data dan dokumen berhasil ditambahkan.');
            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenPlksprodi($id_plks_prodi)
    {
        try {
            // Cari data peningkatan berdasarkan ID
            $plks_prodi = pelaksanaan_prodi::findOrFail($id_plks_prodi);

            // Ambil path file dari model
            $filePath = $plks_prodi->file;

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

    public function edit(String $id_plks_prodi)
    {
        // Ambil data pelaksanaan_prodi yang ingin diedit dengan relasi terkait
        $pelaksanaanprodi = pelaksanaan_prodi::with(['prodi', 'kategori'])
            ->where('id_plks_prodi', $id_plks_prodi)
            ->firstOrFail();

        // Ambil daftar program studi
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();

        // Ambil daftar kategori
        $kategori = kategori::select('id_kategori', 'nama_kategori')->get();

        return view('User.admin.Pelaksanaan.edit_dokumen_pelaksanaan_prodi', [
            'oldData' => $pelaksanaanprodi,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, $id_plks_prodi)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'namafile' => 'required|string|max:255',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'periode_tahunakademik' => 'required|string|max:255',
                'file' => 'file|mimes:pdf,doc,docx,xlsx|max:5120' // Maksimum 5120 KB (5 MB)
            ]);

            // Ambil data pengendalian berdasarkan ID
            $plks_prodi = pelaksanaan_prodi::findOrFail($id_plks_prodi);

            // Proses upload file baru jika ada
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($plks_prodi->file && Storage::exists('public/' . $plks_prodi->file)) {
                    Storage::delete('public/' . $plks_prodi->file);
                }
                // Simpan file baru
                $filePlksProdiPath = $request->file('file')->store('pelaksanaan/prodi', 'public');
                $plks_prodi->file = $filePlksProdiPath;
            }

            // Perbarui data lainnya
            $plks_prodi->update([
                'namafile' => $validatedData['namafile'],
                'id_kategori' => $validatedData['id_kategori'],
                'id_prodi' => $validatedData['id_prodi'],
                'periode_tahunakademik' => $validatedData['periode_tahunakademik'],
            ]);

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data berhasil diperbarui.');
            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_plks_prodi)
    {
        try {
            // Ambil data dokumen berdasarkan ID menggunakan Eloquent
            $dokumen = pelaksanaan_prodi::findOrFail($id_plks_prodi);

            // Hapus file dari storage jika file ada
            if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                Storage::delete('public/' . $dokumen->file);
            }

            // Hapus data dari database
            $dokumen->delete();

            // Berikan notifikasi sukses
            Alert::success('Selesai', 'Dokumen berhasil dihapus.');
            return redirect()->route('pelaksanaan.prodi');
        } catch (ModelNotFoundException $e) {
            // Jika dokumen tidak ditemukan
            Alert::error('error', 'Dokumen tidak ditemukan.');
            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan lain
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
