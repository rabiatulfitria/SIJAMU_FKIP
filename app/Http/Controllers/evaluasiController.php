<?php

namespace App\Http\Controllers;

use App\Models\DokumenEvaluasi;
use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Evaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiController extends Controller
{
    public function index()
    {
        $evaluasi = DokumenEvaluasi::with(['evaluasi', 'prodi'])->get();
        return view('User.admin.Evaluasi.index_evaluasi', compact('evaluasi'));
    }

    public function create()
    {
        $evaluasi = Evaluasi::select('id_evaluasi', 'tanggal_terakhir_dilakukan', 'tanggal_diperbarui')->get();
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();
        return view('User.admin.Evaluasi.tambah_evaluasi', compact('evaluasi', 'prodi'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'nama_dokumen' => 'required|string|max:255',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'manual_namaDokumen' => 'nullable|string',
                'tanggal_terakhir_dilakukan' => 'required|string',
                'tanggal_diperbarui' => 'nullable|date',
                'file_eval' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480',
            ]);

            // Menentukan nama dokumen
            $namaDokumen = $validatedData['nama_dokumen'] === 'Dokumen Lainnya'
                ? $request->input('manual_namaDokumen')
                : $validatedData['nama_dokumen'];

            // Simpan data ke tabel Evaluasi
            $evaluasi = Evaluasi::create([
                'tanggal_terakhir_dilakukan' => $validatedData['tanggal_terakhir_dilakukan'] ? Carbon::parse($validatedData['tanggal_terakhir_dilakukan'])->format('Y-m-d') : null,
                'tanggal_diperbarui' => $validatedData['tanggal_diperbarui'] ? Carbon::parse($validatedData['tanggal_diperbarui'])->format('Y-m-d') : null,
            ]);

            if ($request->hasFile('file_eval')) {
                $file = $request->file('file_eval');
                $namaFile = time() . '-' . $file->getClientOriginalName();
                $path = $file->storeAs('evaluasi', $namaFile, 'public'); 

                DokumenEvaluasi::create([
                    'id_evaluasi' => $evaluasi->id_evaluasi,
                    'nama_dokumen' => $namaDokumen,
                    'id_prodi' => $validatedData['id_prodi'],
                    'file_eval' => $path,
                ]);
            }
            DB::commit();
            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil ditambahkan.');
            return redirect()->route('evaluasi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenevaluasi($id_dokumeneval, $nama_dokumen)
    {
        // Ambil data file berdasarkan id_evaluasi
        $dokumenEval = DokumenEvaluasi::where('id_dokumeneval',$id_dokumeneval)
        ->where('nama_dokumen', $nama_dokumen)
        ->firstOrFail();
        $filePath = storage_path('app/public/' . $dokumenEval->file_eval);

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
                $handle = fopen($filePath, 'rb'); // Buka file dalam mode baca biner
                fpassthru($handle); // Kirim file ke output tanpa buffer tambahan
                fclose($handle); // Tutup file setelah selesai
            }, $dokumenEval->nama_dokumen . '.' . $fileExtension, [
                'Content-Type'              => $mimeTypes[$fileExtension],
                'Content-Disposition'       => 'attachment; filename="' . $dokumenEval->nama_dokumen . '.' . $fileExtension . '"',
                'X-Content-Type-Options'    => 'nosniff',
                'Cache-Control'             => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'                    => 'no-cache',
                'Expires'                   => '0',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length'            => filesize($filePath) // Pastikan ukuran file dikirim
            ]);
        }

        // Untuk PDF & gambar, tampilkan langsung di browser
        return response()->file($filePath, [
            'Content-Type' => $mimeTypes[$fileExtension],
            'Content-Disposition' => 'inline; filename="' . $dokumenEval->nama_dokumen . '.' . $fileExtension . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function edit($id_dokumeneval)
    {
        $dokumenEval = DokumenEvaluasi::with(['evaluasi', 'prodi'])->findOrFail($id_dokumeneval);
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();
        return view('User.admin.Evaluasi.edit_evaluasi', ['oldData' => $dokumenEval, 'prodi' => $prodi]);
    }

    public function update(Request $request, $id_dokumeneval)
    {
        DB::beginTransaction(); // Memulai transaksi database
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nama_dokumen' => 'required|string|max:255',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'manual_namaDokumen' => 'nullable|string',
                'tanggal_terakhir_dilakukan' => 'required|string',
                'tanggal_diperbarui' => 'nullable|date',
                'file_eval' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480',
            ]);

            // Ambil data berdasarkan ID
            $dokumenEval = DokumenEvaluasi::findOrFail($id_dokumeneval);

            $namaDokumen = ($validatedData['nama_dokumen'] === 'Dokumen Lainnya' && $request->filled('manual_namaDokumen'))
                ? $validatedData['manual_namaDokumen']
                : $validatedData['nama_dokumen'];

            // Persiapkan data untuk diupdate
            $updateData = [
                'nama_dokumen' => $namaDokumen,
                'id_prodi' => $validatedData['id_prodi'],
            ];

            // Update di tabel evaluasi
            $dokumenEval->evaluasi()->update(['tanggal_terakhir_dilakukan' => $validatedData['tanggal_terakhir_dilakukan'], 'tanggal_diperbarui' => $validatedData['tanggal_diperbarui']]);

            // Proses upload file baru jika ada
            if ($request->hasFile('file_eval')) {
                $file = $request->file('file_eval');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                // Buat nama file unik agar tidak ditimpa oleh cache browser
                $newFileName = $originalName;
                $counter = 1;
                while (Storage::exists('public/evaluasi/' . $newFileName . '.' . $extension)) {
                    $newFileName = $originalName . '_' . $counter;
                    $counter++;
                }

                // Hapus file lama jika ada
                if ($dokumenEval->file && Storage::exists('public/' . $dokumenEval->file_eval)) {
                    Storage::delete('public/' . $dokumenEval->file);
                }

                // Simpan file baru dengan nama unik
                $filePath = $file->storeAs('evaluasi', $newFileName . '.' . $extension, 'public');

                // Tambahkan path file baru ke data yang akan diperbarui
                $updateData['file_eval'] = $filePath;
            }

            // Update semua data sekaligus
            $dokumenEval->update($updateData);

            // Commit transaksi
            DB::commit();

            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Dokumen berhasil diperbarui.');
            return redirect()->route('evaluasi');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_dokumeneval)
    {
        try {
            // Ambil data dokumen berdasarkan ID, dengan relasi evaluasi
            $dokumen = DokumenEvaluasi::with('evaluasi')->findOrFail($id_dokumeneval);

            if (!$dokumen) {
                Alert::error('Error', 'Dokumen tidak ditemukan.');
                return redirect()->route('evaluasi');
            }

            // Hapus file jika ada
            if ($dokumen->file_eval && Storage::exists('public/' . $dokumen->file_eval)) {
                Storage::delete('public/' . $dokumen->file_eval);
            }

            // Hapus data dokumen_evaluasi
            $dokumen->delete();

            // Hapus data relasi
            if ($dokumen->evaluasi) {
                $dokumen->evaluasi->delete();
            }

            Alert::success('Selesai', 'Dokumen berhasil dihapus.');
            return redirect()->route('evaluasi');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
