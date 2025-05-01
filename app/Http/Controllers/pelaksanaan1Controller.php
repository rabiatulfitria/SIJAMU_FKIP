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
                    'Dokumen Monitoring dan Kegiatan Program MBKM',
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

    public function create(Request $request) 
    {
        $menu = $request->query('menu'); // ambil dari URL

        // Mengirim data ke view
        return view('User.admin.Pelaksanaan.tambah_pelaksanaan_prodi', [
            'menu' => $menu,
            'kategori' => kategori::all(),
            'prodi' => Prodi::all(),
        ]);
    }

    public function create_FormKepuasanMhs() //untuk URL formulir kepuasan mahasiswa
    {
        // Mengambil data prodi dan kategori menggunakan model
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();
        $kategori = kategori::select('id_kategori', 'nama_kategori')->get();

        // Mengirim data ke view
        return view('User.admin.Pelaksanaan.tambah_pelaksanaanprodi_form', compact('prodi', 'kategori'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'namafile' => 'required|string|max:255',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'periode_tahunakademik' => 'required|string|max:255',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480', // Maksimum 20 MB
                'url' => 'nullable|url', // Pastikan URL juga divalidasi
            ]);

            $data = $request->all();
            $filePath = null;

            // Proses upload file 
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName(); // Tambahkan timestamp agar unik
                $filePath = $file->storeAs('pelaksanaan/prodi', $fileName, 'public'); // Simpan file dengan nama asli
            } elseif ($request->filled('url')) {
                $filePath = $request->input('url'); // Simpan URL jika diberikan
            }

            // Simpan data ke database
            pelaksanaan_prodi::create([
                'namafile' => $data['namafile'],
                'id_kategori' => $data['id_kategori'],
                'id_prodi' => $data['id_prodi'],
                'periode_tahunakademik' => $data['periode_tahunakademik'],
                'file' => $filePath, // Menyimpan path file atau URL di kolom yang sama
            ]);

            // Cek nama kategori (Formulir Kepuasan Mahasiswa) untuk menentukan pesan alert + konversi ke stringLowerCase
            $kategori = kategori::find($data['id_kategori']);
            if ($kategori && strtolower($kategori->nama_kategori) === 'formulir kepuasan mahasiswa') {
                Alert::success('Selesai', 'Data dan tautan formulir berhasil ditambahkan.');
            } else {
                Alert::success('Selesai', 'Data dan dokumen berhasil ditambahkan.');
            }


            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenPlksProdi($id_plks_prodi, $namafile)
    {
        // Cari dokumen berdasarkan ID dan nama file
        $dokumenplks_prodi = pelaksanaan_prodi::where('id_plks_prodi', $id_plks_prodi)
            ->where('namafile', $namafile)
            ->firstOrFail();

        // Tentukan path file
        $filePath = storage_path('app/public/' . $dokumenplks_prodi->file);

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
            return response()->download($filePath, $dokumenplks_prodi->namafile . '.' . $fileExtension, [
                'Content-Type'              => $mimeTypes[$fileExtension],
                'Content-Disposition'       => 'attachment; filename="' . $dokumenplks_prodi->namafile . '.' . $fileExtension . '"',
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

    public function edit(Request $request, string $id_plks_prodi)
    {
        $menu = $request->query('menu');

        // Ambil data pelaksanaan_prodi yang ingin diedit dengan relasi terkait
        $pelaksanaanprodi = pelaksanaan_prodi::with(['prodi', 'kategori'])
            ->where('id_plks_prodi', $id_plks_prodi)
            ->firstOrFail();

        // Ambil daftar program studi
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();

        // Ambil daftar kategori
        $kategori = kategori::select('id_kategori', 'nama_kategori')->get();

        return view('User.admin.Pelaksanaan.edit_pelaksanaan_prodi', [
            'oldData' => $pelaksanaanprodi,
            'prodi' => $prodi,
            'kategori' => $kategori,
            'menu' => $menu,
        ]);
    }

    public function edit_FormKepuasanMhs(String $id_plks_prodi)
    {
        // Ambil data pelaksanaan_prodi yang ingin diedit dengan relasi terkait
        $pelaksanaanprodi = pelaksanaan_prodi::with(['prodi', 'kategori'])
            ->where('id_plks_prodi', $id_plks_prodi)
            ->firstOrFail();

        // Ambil daftar program studi
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();

        // Ambil daftar kategori
        $kategori = kategori::select('id_kategori', 'nama_kategori')->get();

        // Cek apakah kolom 'file' menyimpan URL atau bukan/false
        filter_var($pelaksanaanprodi->file, FILTER_VALIDATE_URL) !== false;

        return view('User.admin.Pelaksanaan.edit_pelaksanaanprodi_form', [
            'oldData' => $pelaksanaanprodi,
            'prodi' => $prodi,
            'kategori' => $kategori,
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
                'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480', // Maksimum 20MB
                'url' => 'nullable|url', // Validasi URL
            ]);

            // Ambil data berdasarkan ID
            $plks_prodi = pelaksanaan_prodi::findOrFail($id_plks_prodi);

            // Persiapkan data untuk diupdate
            $updateData = [
                'namafile' => $validatedData['namafile'],
                'id_kategori' => $validatedData['id_kategori'],
                'id_prodi' => $validatedData['id_prodi'],
                'periode_tahunakademik' => $validatedData['periode_tahunakademik'],
            ];

            $filePath = $plks_prodi->file; // Gunakan file lama jika tidak ada perubahan

            // Jika ada file baru yang diunggah
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                // Buat nama file unik agar tidak ditimpa
                $newFileName = $originalName;
                $counter = 1;
                while (Storage::exists('public/pelaksanaan/prodi/' . $newFileName . '.' . $extension)) {
                    $newFileName = $originalName . '_' . $counter;
                    $counter++;
                }

                // Hapus file lama jika sebelumnya adalah file
                if ($filePath && !filter_var($filePath, FILTER_VALIDATE_URL)) {
                    Storage::delete('public/' . $filePath);
                }

                // Simpan file baru dengan nama unik
                $filePath = $file->storeAs('pelaksanaan/prodi', $newFileName . '.' . $extension, 'public');
            }
            // Jika diberikan URL baru
            elseif ($request->filled('url')) {
                // Hapus file lama jika sebelumnya berupa file
                if ($filePath && !filter_var($filePath, FILTER_VALIDATE_URL)) {
                    Storage::delete('public/' . $filePath);
                }

                $filePath = $request->input('url'); // Simpan URL baru
            }

            // Validasi agar tidak menyimpan NULL di kolom file
            if (!$filePath) {
                return redirect()->back()->withInput()->withErrors(['file' => 'Harus mengunggah file atau mengisi URL.']);
            }

            // Tambahkan path file atau URL ke data yang akan diperbarui
            $updateData['file'] = $filePath;

            // Update semua data sekaligus
            $plks_prodi->update($updateData);

            // Cek nama kategori untuk menentukan pesan alert
            $kategori = kategori::find($validatedData['id_kategori']);
            if ($kategori && strtolower($kategori->nama_kategori) === 'formulir kepuasan mahasiswa') {
                Alert::success('Selesai', 'Data dan tautan formulir berhasil diperbarui.');
            } else {
                Alert::success('Selesai', 'Data dan dokumen berhasil diperbarui.');
            }


            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_plks_prodi)
    {
        try {
            // Ambil data dokumen berdasarkan ID
            $dokumen = pelaksanaan_prodi::findOrFail($id_plks_prodi);

            // Cek apakah 'file' berisi URL atau path file di storage
            if ($dokumen->file) {
                $isUrl = filter_var($dokumen->file, FILTER_VALIDATE_URL) !== false;

                // Jika bukan URL (berarti file), maka hapus dari storage
                if (!$isUrl && Storage::exists('public/' . $dokumen->file)) {
                    Storage::delete('public/' . $dokumen->file);
                }
            }

            // Hapus data dari database
            $dokumen->delete();

            $kategori = kategori::find($data['id_kategori']);
            if ($kategori && strtolower($kategori->nama_kategori) === 'formulir kepuasan mahasiswa') {
                Alert::success('Selesai', 'Data dan tautan formulir berhasil diperbarui.');
            } else {
                Alert::success('Selesai', 'Data dan dokumen berhasil diperbarui.');
            }

            return redirect()->route('pelaksanaan.prodi');
        } catch (ModelNotFoundException $e) {
            // Jika dokumen tidak ditemukan
            Alert::error('Error', 'Dokumen tidak ditemukan.');
            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan lain
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
