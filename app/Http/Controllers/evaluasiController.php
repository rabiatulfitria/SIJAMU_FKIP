<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Evaluasi;
use App\Models\FileEval;
use App\Models\NamaFileEval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiController extends Controller
{
    public function index()
    {
        $evaluasi = NamaFileEval::with(['prodi', 'evaluasi', 'fileEval']) //function yg menunjukkan relasi
            ->get();

        return view('User.admin.Evaluasi.index_evaluasi', compact('evaluasi'));
    }

    public function create()
    {
        $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();
        return view('User.admin.Evaluasi.tambah_evaluasi', compact('prodi'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Validasi input
            $validatedData = $request->validate([
                'nama_fileeval' => 'required|string',
                'manual_namaDokumen' => 'nullable|string',
                'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
                'tanggal_terakhir_dilakukan' => 'nullable|date',
                'tanggal_diperbarui' => 'nullable|date',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480',
            ]);

            // Menentukan nama dokumen
            $namaDokumen = $validatedData['nama_fileeval'] === 'Dokumen Lainnya'
                ? $request->input('manual_namaDokumen')
                : $validatedData['nama_fileeval'];

            // Simpan data ke tabel Evaluasi
            $evaluasi = Evaluasi::create([
                'tanggal_terakhir_dilakukan' => $validatedData['tanggal_terakhir_dilakukan'] ? Carbon::parse($validatedData['tanggal_terakhir_dilakukan'])->format('Y-m-d') : null,
                'tanggal_diperbarui' => $validatedData['tanggal_diperbarui'] ? Carbon::parse($validatedData['tanggal_diperbarui'])->format('Y-m-d') : null,
            ]);

            // Simpan data ke tabel namaFileEval
            $namaFileEval = $evaluasi->namaFileEval()->create([
                'nama_fileeval' => $namaDokumen,
                'id_prodi' => $validatedData['id_prodi']
            ]);

            // Simpan file jika ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $namaFile = time() . '-' . $file->getClientOriginalName();
                $file->storeAs('evaluasi', $namaFile, 'public');

                $namaFileEval->fileEval()->create(['file' => $namaFile]);
            } else {
                $namaFileEval->fileEval()->create(['file' => '']);
            }

            DB::commit(); // Simpan transaksi ke database

            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil ditambahkan.');
            return redirect()->route('evaluasi');
        } catch (\Exception $e) {
            DB::rollBack(); // Kembalikan data jika terjadi error
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenevaluasi($id_evaluasi)
    {
        // Ambil data file berdasarkan id_evaluasi
        $dokumenEval = FileEval::whereHas('namaFileEval.evaluasi', function ($query) use ($id_evaluasi) {
            $query->where('id_evaluasi', $id_evaluasi);
        })->firstOrFail();

        $filePath = storage_path('app/public/evaluasi/' . $dokumenEval->file);

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
            }, $dokumenEval->nama_fileeval . '.' . $fileExtension, [
                'Content-Type'              => $mimeTypes[$fileExtension],
                'Content-Disposition'       => 'attachment; filename="' . $dokumenEval->nama_fileeval . '.' . $fileExtension . '"',
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
            'Content-Disposition' => 'inline; filename="' . $dokumenEval->nama_fileeval . '.' . $fileExtension . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function edit(String $id_evaluasi)
    {
        try {
            // Ambil data evaluasi berdasarkan id_evaluasi
            $dataEvaluasi = Evaluasi::with(['namaFileEval.fileEval'])->findOrFail($id_evaluasi);

            // Ambil daftar program studi untuk dropdown
            $prodi = Prodi::select('id_prodi', 'nama_prodi')->get();

            return view('User.admin.Evaluasi.edit_evaluasi', [
                'oldData' => $dataEvaluasi, //Menampilkan Halaman Edit dengan Data Sebelumnya
                'prodi' => $prodi,
                'file' => optional(optional($dataEvaluasi->namaFileEval->first())->fileEval->first())->file, // optional() mencegah error jika ada data yang null
                'namaFileEval' => optional($dataEvaluasi->namaFileEval->first())->nama_fileeval ?? '',
                'id_prodi' => optional($dataEvaluasi->namaFileEval->first())->id_prodi,
                'tanggal_terakhir_dilakukan' => $dataEvaluasi->tanggal_terakhir_dilakukan,
                'tanggal_diperbarui' => $dataEvaluasi->tanggal_diperbarui,
            ]);
        } catch (\Exception $e) {
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id_evaluasi)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'nama_fileeval' => 'required|string',
            'manual_namaDokumen' => 'nullable|string',
            'id_prodi' => 'required|exists:tabel_prodi,id_prodi',
            'tanggal_terakhir_dilakukan' => 'nullable|date',
            'tanggal_diperbarui' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480',
        ]);


        DB::beginTransaction();
        try {
            $evaluasi = Evaluasi::findOrFail($id_evaluasi);
            $evaluasi->update([
                'tanggal_terakhir_dilakukan' => $validatedData['tanggal_terakhir_dilakukan'] ? Carbon::parse($validatedData['tanggal_terakhir_dilakukan'])->format('Y-m-d') : null,
                'tanggal_diperbarui' => $validatedData['tanggal_diperbarui'] ? Carbon::parse($validatedData['tanggal_diperbarui'])->format('Y-m-d') : null,
                'updated_at' => now(),
            ]);

            $namaDokumen = $validatedData['nama_fileeval'] === 'Dokumen Lainnya' && $request->filled('manual_namaDokumen')
                ? $request->input('manual_namaDokumen')
                : $validatedData['nama_fileeval'];

            $namaFileEval = NamaFileEval::updateOrCreate(
                ['id_evaluasi' => $id_evaluasi],
                [
                    'nama_fileeval' => $namaDokumen,
                    'id_prodi' => $validatedData['id_prodi'],
                    'updated_at' => now(),
                ]
            );

            if ($request->hasFile('file')) {
                $namaFileEval->fileEval()->delete();
                foreach ($request->file('file') as $file) {
                    $namaFile = time() . '-' . $file->getClientOriginalName();
                    $file->storeAs('evaluasi', $namaFile, 'public');
                    FileEval::create([
                        'file' => $namaFile,
                        'id_nfeval' => $namaFileEval->id_nfeval,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            DB::commit();
            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil diperbarui.');
            // dd(Evaluasi::find($id_evaluasi));
            return redirect()->route('evaluasi');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id_evaluasi)
    {
        try {
            $evaluasi = Evaluasi::with('namaFileEval.fileEval')->findOrFail($id_evaluasi);

            if ($evaluasi->namaFileEval->isNotEmpty()) { // Pastikan ada data sebelum mengakses
                foreach ($evaluasi->namaFileEval as $namaFileEval) { // Looping pada koleksi namaFileEval
                    foreach ($namaFileEval->fileEval as $file) { // Looping pada koleksi fileEval
                        if (!empty($file->file)) {
                            Storage::disk('public')->delete('evaluasi/' . $file->file);
                        }
                    }
                    $namaFileEval->fileEval()->delete(); // Hapus file_eval terkait
                    $namaFileEval->delete(); // Hapus nama_file_eval terkait
                }
            }

            $evaluasi->delete(); // Hapus evaluasi utama

            Alert::success('Selesai', 'Data evaluasi dan dokumen berhasil dihapus.');
            return redirect()->route('evaluasi');
        } catch (\Exception $e) {
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
