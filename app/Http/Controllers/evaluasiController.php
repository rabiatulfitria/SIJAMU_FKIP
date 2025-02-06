<?php

namespace App\Http\Controllers;

use App\Models\Evaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class evaluasiController extends Controller
{
    public function index()
    {
        // Ambil data gabungan dari tabel nama_file_eval, evaluasi, dan file_eval
        $evaluasi = DB::table('nama_file_eval')
            ->join('tabel_prodi', 'nama_file_eval.namaprodi', '=', 'tabel_prodi.id_prodi')
            ->join('evaluasis', 'nama_file_eval.id_evaluasi', '=', 'evaluasis.id_evaluasi')
            ->join('file_eval', 'nama_file_eval.id_nfeval', '=', 'file_eval.id_nfeval')
            ->select(
                'tabel_prodi.nama_prodi as namaprodi',
                'nama_file_eval.nama_fileeval as namaDokumen_evaluasi',
                'evaluasis.id_evaluasi as id_evaluasi',
                'evaluasis.tanggal_terakhir_dilakukan as tanggal_terakhir_dilakukan',
                'evaluasis.tanggal_diperbarui as tanggal_diperbarui',
                'file_eval.files as unggahan' //nama 'unggahan' ini harus sama dengan nama row di blade.
            )
            ->get();

        // Kembalikan data ke view
        return view('User.admin.Evaluasi.index_evaluasi', compact('evaluasi'));
    }

    public function create()
    {
        // Mengambil data nama_prodi dari tabel_prodi
        $prodi = DB::table('tabel_prodi')->select('id_prodi', 'nama_prodi')->get();
        return view('User.admin.Evaluasi.tambah_evaluasi', compact('prodi'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'namaDokumen_evaluasi' => 'required|string',
                'manual_namaDokumen' => 'nullable|string',
                'namaprodi' => 'required|exists:tabel_prodi,id_prodi',
                'tanggal_terakhir_dilakukan' => 'nullable|date',
                'tanggal_diperbarui' => 'nullable|date',
                'unggahan_dokumen.*' => 'nullable|mimes:doc,docx,xls,xlsx,pdf|max:5120', // Maksimum 5120 KB (5 MB)
            ]);

            // Menentukan nama dokumen, apakah dari dropdown atau input manual
            $namaDokumen = $validatedData['namaDokumen_evaluasi'];
            if ($namaDokumen === 'Dokumen Lainnya' && $request->filled('manual_namaDokumen')) {
                $namaDokumen = $request->input('manual_namaDokumen');
            }

            // Simpan data ke tabel evaluasi menggunakan query builder
            $idEvaluasi = DB::table('evaluasis')->insertGetId([
                'tanggal_terakhir_dilakukan' => $validatedData['tanggal_terakhir_dilakukan'],
                'tanggal_diperbarui' => $validatedData['tanggal_diperbarui'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan nama dokumen ke tabel nama_file_eval dengan id dari evaluasi
            $idNfeval = DB::table('nama_file_eval')->insertGetId([
                'nama_fileeval' => $namaDokumen,  // Nama dokumen yang disimpan
                'namaprodi' => $validatedData['namaprodi'],
                'id_evaluasi' => $idEvaluasi,     // Mengambil id_evaluasi dari tabel evaluasi
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mengupload file dan simpan ke tabel file_eval
            if ($request->hasFile('unggahan_dokumen')) { //apakah request HTTP (form) memiliki nama input unggahan_dokumen
                foreach ($request->file('unggahan_dokumen') as $file) {
                    // Simpan file
                    $namaFile = time() . '-' . $file->getClientOriginalName();
                    $file->storeAs('evaluasi', $namaFile, 'public');

                    // Simpan nama file ke tabel file_eval
                    DB::table('file_eval')->insert([
                        'files' => $namaFile,       // Nama file yang diunggah
                        'id_nfeval' => $idNfeval,   // ID dari nama_file_eval
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Jika tidak ada file yang diunggah, simpan data dengan field files kosong
                DB::table('file_eval')->insert([
                    'files' => '',            // Field file kosong
                    'id_nfeval' => $idNfeval, // ID dari nama_file_eval
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil ditambahkan.');
            return redirect()->route('evaluasi');

        } catch (\Exception $e) {
            // Menangkap semua error dan menampilkan pesan kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function lihatdokumenevaluasi($id_evaluasi)
    {
        $evaluasi = Evaluasi::findOrFail($id_evaluasi);
        $filePaths = json_decode($evaluasi->unggahan_dokumen, true);

        if (is_array($filePaths) && !empty($filePaths)) {
            $file = $filePaths[0];

            if (Storage::disk('local')->exists($file)) {
                return response()->file(storage_path('app' . $file));
            } else {
                abort(404, 'File tidak ditemukan.');
            }
        }
    }

    public function edit(String $id_evaluasi)
    {
        try {
            // Ambil data evaluasi berdasarkan id_evaluasi
            $dataEvaluasi = DB::table('evaluasis')
                ->where('id_evaluasi', $id_evaluasi)
                ->first();

            // Ambil data nama_file_eval berdasarkan id_evaluasi
            $namaFileEval = DB::table('nama_file_eval')
                ->where('id_evaluasi', $id_evaluasi)
                ->first();

            // Ambil data nama_file_eval berdasarkan id_nfeval di tabel fileeval
            $fileevalnya = null;
            if ($namaFileEval) {
                $fileevalnya = DB::table('file_eval')
                    ->where('id_nfeval', $namaFileEval->id_nfeval)
                    ->first();
            }

            if (!$namaFileEval) {
                $namaFileEval = (object) ['nama_fileeval' => ''];
            }

            // Ambil daftar program studi untuk dropdown
            $prodi = DB::table('tabel_prodi')->select('id_prodi', 'nama_prodi')->get();
            
            // Pastikan untuk mengembalikan data lengkap (data evaluasi + nama file evaluasi)
            return view('User.admin.Evaluasi.edit_evaluasi', [
                'oldData' => $dataEvaluasi, 'prodi' => $prodi, // Data evaluasi yang diambil dari tabel evaluasis
                'files' => $fileevalnya ? $fileevalnya->files : null,
                'namaFileEval' => $namaFileEval->nama_fileeval,  // Mengembalikan nama_fileeval
                'namaprodi' => $namaFileEval->namaprodi,
                'tanggal_terakhir_dilakukan' => $dataEvaluasi->tanggal_terakhir_dilakukan,  // Tanggal terakhir dilakukan
                'tanggal_diperbarui' => $dataEvaluasi->tanggal_diperbarui,  // Tanggal diperbarui
            ]);

        } catch (\Exception $e) {
            // Menangkap error jika terjadi masalah
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id_evaluasi)
    {
        try {
            // Validasi input dari form
            $validatedData = $request->validate([
                'nama_fileeval' => 'required|string',
                'manual_namaDokumen' => 'nullable|string',
                'namaprodi' => 'required|exists:tabel_prodi,id_prodi',
                'tanggal_terakhir_dilakukan' => 'nullable|date',
                'tanggal_diperbarui' => 'nullable|date',
                'unggahan_dokumen.*' => 'nullable|mimes:doc,docx,xls,xlsx,pdf|max:5120', //Maksimum 5120 KB (5 MB)
            ]);

            // Cek apakah nama dokumen menggunakan dropdown atau input manual
            $namaDokumen = $validatedData['nama_fileeval'];
            if ($namaDokumen === 'Dokumen Lainnya' && $request->filled('manual_namaDokumen')) {
                $namaDokumen = $request->input('manual_namaDokumen');
            }

            // Update data evaluasi di tabel evaluasis
            DB::table('evaluasis')
                ->where('id_evaluasi', $id_evaluasi)
                ->update([
                    'tanggal_terakhir_dilakukan' => $validatedData['tanggal_terakhir_dilakukan'],
                    'tanggal_diperbarui' => $validatedData['tanggal_diperbarui'],
                    'updated_at' => now(),
                ]);

            // Update nama dokumen di tabel nama_file_eval
            $namaFileEval = DB::table('nama_file_eval')
                ->where('id_evaluasi', $id_evaluasi)
                ->first();

            if ($namaFileEval) {
                DB::table('nama_file_eval')
                    ->where('id_evaluasi', $id_evaluasi)
                    ->update([
                        'nama_fileeval' => $namaDokumen,  // Update nama dokumen
                        'namaprodi' => $validatedData['namaprodi'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Jika tidak ada data di nama_file_eval, buat baru
                DB::table('nama_file_eval')->insert([
                    'nama_fileeval' => $namaDokumen,
                    'namaprodi' => $validatedData['namaprodi'],
                    'id_evaluasi' => $id_evaluasi,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Cek apakah ada file baru yang diunggah
            if ($request->hasFile('unggahan_dokumen')) {
                // Hapus file lama jika ada
                DB::table('file_eval')->where('id_nfeval', $namaFileEval->id_nfeval)->delete();

                foreach ($request->file('unggahan_dokumen') as $file) {
                    // Simpan file baru
                    $namaFile = time() . '-' . $file->getClientOriginalName();
                    $file->storeAs('evaluasi', $namaFile, 'public');

                    // Simpan nama file ke tabel file_eval
                    DB::table('file_eval')->insert([
                        'files' => $namaFile,       // Nama file yang diunggah
                        'id_nfeval' => $namaFileEval->id_nfeval,   // ID dari nama_file_eval
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Jika tidak ada file baru yang diunggah, file lama tetap digunakan
                $existingFiles = DB::table('file_eval')->where('id_nfeval', $namaFileEval->id_nfeval)->get();

                if ($existingFiles->isEmpty()) {
                    // Jika tidak ada file sebelumnya, masukkan data dengan file kosong
                    DB::table('file_eval')->insert([
                        'files' => '',  // Field file kosong
                        'id_nfeval' => $namaFileEval->id_nfeval,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Jika update berhasil
            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil diperbarui.');
            return redirect()->route('evaluasi');  // Ubah dengan route yang sesuai

        } catch (\Exception $e) {
            // Jika ada error, tampilkan pesan error
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_evaluasi)
    {
        try {
            // Ambil data nama_file_eval dan file_eval yang terkait dengan evaluasi ini
            $namaFileEval = DB::table('nama_file_eval')->where('id_evaluasi', $id_evaluasi)->first();

            if ($namaFileEval) {
                // Ambil semua file yang terkait dengan nama_file_eval ini
                $files = DB::table('file_eval')->where('id_nfeval', $namaFileEval->id_nfeval)->get();

                // Hapus file dari folder penyimpanan
                foreach ($files as $file) {
                    if (!empty($file->files)) {
                        // Pastikan file tidak kosong sebelum dihapus
                        Storage::disk('public')->delete('evaluasi/' . $file->files);
                    }
                }

                // Hapus data dari tabel file_eval
                DB::table('file_eval')->where('id_nfeval', $namaFileEval->id_nfeval)->delete();

                // Hapus data dari tabel nama_file_eval
                DB::table('nama_file_eval')->where('id_evaluasi', $id_evaluasi)->delete();
            }

            // Hapus data dari tabel evaluasis
            DB::table('evaluasis')->where('id_evaluasi', $id_evaluasi)->delete();

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil dihapus.');
            return redirect()->route('evaluasi');  // Ubah dengan route yang sesuai

        } catch (\Exception $e) {
            // Tampilkan pesan error jika terjadi kesalahan
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}
