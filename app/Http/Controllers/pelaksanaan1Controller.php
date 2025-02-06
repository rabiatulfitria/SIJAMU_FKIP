<?php

namespace App\Http\Controllers;

use App\Models\pelaksanaan_prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class pelaksanaan1Controller extends Controller
{
    public function index()
    {
        $plks_prodi = DB::table('pelaksanaan_prodi')
            ->join('tabel_prodi', 'pelaksanaan_prodi.namaprodi', '=', 'tabel_prodi.id_prodi')
            ->join('kategori', 'pelaksanaan_prodi.nama_kategori', '=', 'kategori.id_kategori')
            ->join('pelaksanaans', 'pelaksanaan_prodi.periode_tahunakademik', '=', 'pelaksanaans.id_plks')
            ->select('pelaksanaan_prodi.*', 'tabel_prodi.nama_prodi', 'kategori.nama_kategori', 'pelaksanaans.periode_tahunakademik as periode_ta')
            ->where(function ($query) {
                $query->where('pelaksanaan_prodi.nama_kategori', 'Renstra Program Studi')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Laporan Kinerja Program Studi')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Dokumen Kurikulum')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Rencana Pembelajaran Semester (RPS)')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Dokumen Monitoring dan Evaluasi Kegiatan Program MBKM')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Capaian Pembelajaran Lulusan (CPL)')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Panduan RPS')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Panduan Mutu Soal')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Panduan Kisi Kisi Soal')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Formulir Kepuasan Mahasiswa')
                    ->orWhere('pelaksanaan_prodi.nama_kategori', 'Dokumen Monitoring dan Evaluasi Ketercapaian Standar Layanan Kemahasiswaan');
            })
            ->get();

        // Memisahkan hasil berdasarkan kategori
        $renstraProgramStudi = $plks_prodi->where('nama_kategori', 'Renstra Program Studi');
        $laporanKinerjaProgramStudi = $plks_prodi->where('nama_kategori', 'Laporan Kinerja Program Studi');
        $dokumenKurikulum = $plks_prodi->where('nama_kategori', 'Dokumen Kurikulum');
        $rps = $plks_prodi->where('nama_kategori', 'Rencana Pembelajaran Semester (RPS)');
        $monitoringMbkm = $plks_prodi->where('nama_kategori', 'Dokumen Monitoring dan Evaluasi Kegiatan Program MBKM');
        $cpl = $plks_prodi->where('nama_kategori', 'Capaian Pembelajaran Lulusan (CPL)');
        $panduanRps = $plks_prodi->where('nama_kategori', 'Panduan RPS');
        $panduanMutuSoal = $plks_prodi->where('nama_kategori', 'Panduan Mutu Soal');
        $panduanKisiKisi = $plks_prodi->where('nama_kategori', 'Panduan Kisi Kisi Soal');
        $formulirKepuasan = $plks_prodi->where('nama_kategori', 'Formulir Kepuasan Mahasiswa');
        $monitoringKemahasiswaan = $plks_prodi->where('nama_kategori', 'Dokumen Monitoring dan Evaluasi Ketercapaian Standar Layanan Kemahasiswaan');

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
            'monitoringKemahasiswaan',
        ));
    }

    public function tambahPelaksanaan()
    {
        $prodi = DB::table('tabel_prodi')->select('id_prodi', 'nama_prodi')->get();
        $kategori = DB::table('kategori')->select('id_kategori', 'nama_kategori')->get();
        $periode = DB::table('pelaksanaans')->select('id_plks', 'periode_tahunakademik')->get();

        return view('User.admin.Pelaksanaan.tambah_dokumen_pelaksanaan', compact('prodi', 'kategori', 'periode'));
    }

    public function simpanPelaksanaan(Request $request)
    { {
            // Validasi input
            $validatedData = $request->validate([
                'namafile' => 'required|string|max:255',
                'nama_kategori' => 'required|exists:kategori,id_kategori',
                'nama_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'periode_ta' => 'required|string|max:255',
                'files' => 'required',
                'files.*' => 'file|mimes:pdf,doc,docx,xlsx,url|max:5120' //Maksimum 5120 KB (5 MB)
            ]);

            // Simpan data ke tabel pelaksanaans menggunakan query builder
            $Pelaksanaan = DB::table('pelaksanaans')->insertGetId([
                'periode_tahunakademik' => $validatedData['periode_ta'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            try {
                DB::beginTransaction();

                foreach ($request->file('files') as $file) {
                    // Generate unique file name
                    $namaFile = time() . '-' . $file->getClientOriginalName();

                    // Store file in the 'public/pelaksaan/prodi' directory
                    $path = $file->storeAs('pelaksanaan/prodi', $namaFile, 'public');

                    // Insert data into 'pelaksanaan_prodi' table
                    DB::table('pelaksanaan_prodi')->insert([
                        'namafile' => $validatedData['namafile'],
                        'periode_tahunakademik' => $Pelaksanaan,
                        'nama_kategori' => $validatedData['nama_kategori'],
                        'namaprodi' => $validatedData['nama_prodi'],
                        'file' => $path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                DB::commit();
                Alert::success('success', 'Dokumen berhasil ditambahkan.');
                return redirect()->route('pelaksanaan.prodi');
            } catch (\Exception $e) {
                DB::rollBack();
                Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }
    }

    public function lihatdokumenPlksprodi($id_plks_prodi)
    {
        $plks_prodi = pelaksanaan_prodi::findOrFail($id_plks_prodi);
        $filePaths = json_decode($plks_prodi->files, true);

        if (is_array($filePaths) && !empty($filePaths)) {
            $file = $filePaths[0];

            if (Storage::disk('local')->exists($file)) {
                return response()->file(storage_path('app' . $file));
            } else {
                abort(404, 'File not found.');
            }
        }
    }

    public function editPelaksanaan(Request $request, $id)
    {
        // Ambil data pelaksanaan_prodi yang ingin diedit
        $pelaksanaanprodi = DB::table('pelaksanaan_prodi')
            ->join('tabel_prodi', 'pelaksanaan_prodi.namaprodi', '=', 'tabel_prodi.id_prodi')
            ->join('kategori', 'pelaksanaan_prodi.nama_kategori', '=', 'kategori.id_kategori')
            ->join('pelaksanaans', 'pelaksanaan_prodi.periode_tahunakademik', '=', 'pelaksanaans.id_plks')
            ->select('pelaksanaan_prodi.*', 'tabel_prodi.nama_prodi', 'kategori.nama_kategori', 'pelaksanaans.periode_tahunakademik as periode_ta')
            ->where('pelaksanaan_prodi.id_plks_prodi', '=', $id)
            ->first();

        $prodi = DB::table('tabel_prodi')->select('id_prodi', 'nama_prodi')->get();
        $kategori = DB::table('kategori')->select('id_kategori', 'nama_kategori')->get();
        $periode = DB::table('pelaksanaans')->select('id_plks', 'periode_tahunakademik');

        return view('User.admin.Pelaksanaan.edit_dokumen_pelaksanaan', compact('pelaksanaanprodi', 'prodi', 'kategori', 'periode'));
    }

    public function updatePelaksanaan(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'namafile' => 'required|string|max:255',
            'nama_kategori' => 'required|exists:kategori,id_kategori',
            'nama_prodi' => 'required|exists:tabel_prodi,id_prodi',
            'periode_ta' => 'required|string|max:255',
            'files' => 'required',
            'files.*' => 'file|mimes:pdf,doc,docx,xlsx,url|max:5120' //Maksimum 5120 KB (5 MB)
        ]);

        try {
            DB::beginTransaction();

            // Ambil data lama dari tabel
            $dokumen = DB::table('pelaksanaan_prodi')->where('id_plks_prodi', $id)->first();

            // Update tabel standar_institusi
            DB::table('pelaksanaan_prodi')
                ->where('id_plks_prodi', $id)
                ->update([
                    'namafile' => $validatedData['namafile'],
                    'nama_kategori' => $validatedData['nama_kategori'],
                    'namaprodi' => $validatedData['nama_prodi'],
                    'updated_at' => now()
                ]);

            // Update tabel penetapans
            DB::table('pelaksanaans')->where('id_plks', $dokumen->id_plks)->update([
                'periode_tahunakademik' => $validatedData['periode_ta'],
                'updated_at' => now(),
            ]);

            // Jika ada file baru diunggah, hapus file lama dan simpan file baru
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Hapus file lama jika ada
                    if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                        Storage::delete('public/' . $dokumen->file);
                    }

                    // Generate unique file name
                    $namaFile = time() . '-' . $file->getClientOriginalName();
                    $path = $file->storeAs('pelaksanaan/prodi', $namaFile, 'public');

                    // Update path file baru di database
                    DB::table('pelaksanaan_prodi')
                        ->where('id_plks_prodi', $id)
                        ->update(['file' => $path]);
                }
            }

            DB::commit();
            Alert::success('success', 'Dokumen berhasil diupdate.');
            return redirect()->route('pelaksanaan.prodi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deletePelaksanaan(String $id)
    {
        try {
            // Ambil data dokumen berdasarkan id
            $dokumen = DB::table('pelaksanaan_prodi')->where('id_plks_prodi', $id)->first();

            // Pastikan data dokumen ditemukan
            if ($dokumen) {
                // Hapus file dari storage
                if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                    Storage::delete('public/' . $dokumen->file);
                }

                // Hapus data dari tabel pelaksanaan_prodi
                DB::table('pelaksanaan_prodi')->where('id_plks_prodi', $id)->delete();


                Alert::success('success', 'Dokumen berhasil dihapus.');
                return redirect()->route('pelaksanaan.prodi');
            } else {

                Alert::success('error', 'Dokumen gagal dihapus.');
                return redirect()->route('pelaksanaan.prodi');
            }
        } catch (\Exception $e) {

            Alert::success('error', 'Dokumen gagal dihapus.');
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function indexFakultas()
    {
        $renstraFakultas = DB::table('pelaksanaan_fakultas')
            ->join('kategori', 'pelaksanaan_fakultas.nama_kategori', '=', 'kategori.id_kategori')
            ->join('pelaksanaans', 'pelaksanaan_fakultas.periode_tahunakademik', '=', 'pelaksanaans.id_plks')
            ->select('pelaksanaan_fakultas.*', 'kategori.nama_kategori', 'pelaksanaans.periode_tahunakademik as periode_ta')
            ->where('pelaksanaan_fakultas.nama_kategori', 'Renstra Fakultas')
            ->get();

        $laporanKinerjaFakultas = DB::table('pelaksanaan_fakultas')
            ->join('kategori', 'pelaksanaan_fakultas.nama_kategori', '=', 'kategori.id_kategori')
            ->join('pelaksanaans', 'pelaksanaan_fakultas.periode_tahunakademik', '=', 'pelaksanaans.id_plks')
            ->select('pelaksanaan_fakultas.*', 'kategori.nama_kategori', 'pelaksanaans.periode_tahunakademik as periode_ta')
            ->where('pelaksanaan_fakultas.nama_kategori', 'Laporan Kinerja Fakultas')
            ->get();

        return view('User.admin.Pelaksanaan.index_fakultas', compact(
            'renstraFakultas',
            'laporanKinerjaFakultas'
        ));
    }

    public function tambahPelaksanaanFakultas()
    {
        $prodi = DB::table('tabel_prodi')->select('id_prodi', 'nama_prodi')->get();
        $kategori = DB::table('kategori')->select('id_kategori', 'nama_kategori')->get();
        $periode = DB::table('pelaksanaans')->select('id_plks', 'periode_tahunakademik');

        return view('User.admin.Pelaksanaan.tambah_dokumen_pelaksanaan_fakultas', compact('prodi', 'kategori', 'periode'));
    }

    public function simpanPelaksanaanFakultas(Request $request)
    { {
            // Validasi input
            $validatedData = $request->validate([
                'namafile' => 'required|string|max:255',
                'nama_kategori' => 'required|exists:kategori,id_kategori',
                'periode_ta' => 'required|string|max:255',
                'files' => 'required',
                'files.*' => 'file|mimes:pdf,doc,docx,xlsx,url|max:5120' //Maksimum 5120 KB (5 MB)
            ]);

            // Simpan data ke tabel pelaksanaans menggunakan query builder
            $Pelaksanaan = DB::table('pelaksanaans')->insertGetId([
                'periode_tahunakademik' => $validatedData['periode_ta'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            try {
                DB::beginTransaction();

                foreach ($request->file('files') as $file) {
                    // Generate unique file name
                    $namaDokumen = time() . '-' . $file->getClientOriginalName();

                    // Store file in the 'public/pelaksanaan' directory
                    $path = $file->storeAs('pelaksanaan/fakultas', $namaDokumen, 'public');

                    // Insert data into 'pelaksanaan_fakultas' table
                    DB::table('pelaksanaan_fakultas')->insert([
                        'namafile' => $validatedData['namafile'],
                        'periode_tahunakademik' => $Pelaksanaan,
                        'nama_kategori' => $validatedData['nama_kategori'],
                        'file' => $path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                DB::commit();
                Alert::success('success', 'Dokumen berhasil ditambahkan.');
                return redirect()->route('pelaksanaan.fakultas');
            } catch (\Exception $e) {
                DB::rollBack();
                Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }
    }

    public function editPelaksanaanFakultas(Request $request, $id)
    {
        // Ambil data pelaksanaan_fakultas yang ingin diedit
        $pelaksanaanfakultas = DB::table('pelaksanaan_fakultas')
            ->where('id_plks_fklts', '=', $id)
            ->first();

        $kategori = DB::table('kategori')->select('id_kategori', 'nama_kategori')->get();
        $periode = DB::table('pelaksanaans')->select('id_plks', 'periode_tahunakademik');

        return view('User.admin.Pelaksanaan.edit_dokumen_pelaksanaan_fakultas', compact('pelaksanaanfakultas', 'kategori', 'periode'));
    }

    public function updatePelaksanaanFakultas(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'namafile' => 'required|string|max:255',
            'nama_kategori' => 'required|exists:kategori,id_kategori',
            'periode_ta' => 'required|string|max:255',
            'files' => 'required',
            'files.*' => 'file|mimes:pdf,doc,docx,xlsx,url|max:5120' //Maksimum 5120 KB (5 MB)
        ]);

        try {
            DB::beginTransaction();

            // Ambil data lama dari tabel
            $dokumen = DB::table('pelaksanaan_fakultas')->where('id_plks_fklts', $id)->first();

            // Update tabel standar_institusi
            DB::table('pelaksanaan_fakultas')
                ->where('id_plks_fklts', $id)
                ->update([
                    'namafile' => $validatedData['namafile'],
                    'nama_kategori' => $validatedData['nama_kategori'],
                    'updated_at' => now()
                ]);

            // Update tabel penetapans
            DB::table('pelaksanaans')->where('id_plks', $dokumen->id_plks)->update([
                'periode_tahunakademik' => $validatedData['periode_ta'],
                'updated_at' => now(),
            ]);

            // Jika ada file baru diunggah, hapus file lama dan simpan file baru
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Hapus file lama jika ada
                    if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                        Storage::delete('public/' . $dokumen->file);
                    }

                    // Generate unique file name
                    $namaFile = time() . '-' . $file->getClientOriginalName();
                    $path = $file->storeAs('pelaksanaan/fakultas', $namaFile, 'public');

                    // Update path file baru di database
                    DB::table('pelaksanaan_fakultas')
                        ->where('id_plks_fklts', $id)
                        ->update(['file' => $path]);
                }
            }

            DB::commit();
            Alert::success('success', 'Dokumen berhasil diupdate.');
            return redirect()->route('pelaksanaan.fakultas');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deletePelaksanaanFakultas(String $id)
    {
        try {
            // Ambil data dokumen berdasarkan id
            $dokumen = DB::table('pelaksanaan_fakultas')->where('id_plks_fklts', $id)->first();

            // Pastikan data dokumen ditemukan
            if ($dokumen) {
                // Hapus file dari storage
                if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                    Storage::delete('public/' . $dokumen->file);
                }

                // Hapus data dari tabel pelaksanaan_fakultas
                DB::table('pelaksanaan_fakultas')->where('id_plks_fklts', $id)->delete();


                Alert::success('success', 'Dokumen berhasil dihapus.');
                return redirect()->route('pelaksanaan.fakultas');
            } else {

                Alert::success('error', 'Dokumen gagal dihapus.');
                return redirect()->route('pelaksanaan.fakultas');
            }
        } catch (\Exception $e) {

            Alert::success('error', 'Dokumen gagal dihapus.');
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
