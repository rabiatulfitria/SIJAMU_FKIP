<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Penetapan;
use Illuminate\Http\Request;
use App\Models\StandarInstitut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class StandarController extends Controller
{
    public function index()
    {
        $dokumenp1 = StandarInstitut::with('penetapan')->get();
        return view('User.admin.Penetapan.standarinstitusi', compact('dokumenp1'));
    }

    public function create()
    {
        $penetapans = Penetapan::select('id_penetapan', 'tanggal_ditetapkan')->get();
        return view('User.admin.Penetapan.tambah_standarspmi', compact('penetapans'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'namafile' => 'required|string|max:255',
            'kategori' => 'required|string',
            'tanggal_ditetapkan' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:20480'
        ]);

        DB::beginTransaction();
        try {
            $penetapans = Penetapan::create(['tanggal_ditetapkan' => $validatedData['tanggal_ditetapkan']]);

            if ($request->hasFile('file')) {
                $file = $request->file('file'); // Ambil file dari request
                $namaFile = time() . '-' . $file->getClientOriginalName(); // Tambahkan timestamp agar unik
                $path = $file->storeAs('standar', $namaFile, 'public'); // Simpan file di storage            

                StandarInstitut::create([
                    'id_penetapan' => $penetapans->id_penetapan,
                    'namafile' => $validatedData['namafile'],
                    'file' => $path,
                ]);
            }

            DB::commit();
            Alert::success('Selesai', 'Dokumen berhasil ditambahkan.');
            return redirect()->route('penetapan.standar');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenstandar($id_standarinstitut)
    {
        $dokumenp1 = StandarInstitut::findOrFail($id_standarinstitut);
        $filePath = storage_path('app/public/' . $dokumenp1->file);

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
            }, $dokumenp1->namafile . '.' . $fileExtension, [
                'Content-Type'              => $mimeTypes[$fileExtension],
                'Content-Disposition'       => 'attachment; filename="' . $dokumenp1->namafile . '.' . $fileExtension . '"',
                'X-Content-Type-Options'    => 'nosniff',
                'Cache-Control'             => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'                    => 'no-cache',
                'Expires'                   => '0',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length'            => filesize($filePath) // Pastikan ukuran file dikirim
            ]);
        }

        return response()->file($filePath, [
            'Content-Type' => $mimeTypes[$fileExtension],
            'Content-Disposition' => 'inline; filename="' . $dokumenp1->namafile . '.' . $fileExtension . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function edit($id_standarinstitut)
    {
        $dokumenp1 = StandarInstitut::with('penetapan')->findOrFail($id_standarinstitut);
        return view('User.admin.Penetapan.edit_standarinstitusi', ['oldData' => $dokumenp1]);
    }

    public function update(Request $request, $id_standarinstitut)
    {
        DB::beginTransaction(); // Memulai transaksi database
        try {
            // Validasi input
            $validatedData = $request->validate([
                'namafile' => 'required|string|max:255',
                'tanggal_ditetapkan' => 'nullable|date',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:20480' // Maksimum 20MB
            ]);
    
            // Ambil data berdasarkan ID
            $dokumen = StandarInstitut::findOrFail($id_standarinstitut);
    
            // Persiapkan data untuk diupdate
            $updateData = [
                'namafile' => $validatedData['namafile'],
            ];
    
            // Update tanggal_ditetapkan pada relasi penetapan
            $dokumen->penetapan()->update(['tanggal_ditetapkan' => $validatedData['tanggal_ditetapkan']]);
    
            // Proses upload file baru jika ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
    
                // Buat nama file unik agar tidak ditimpa oleh cache browser
                $newFileName = $originalName;
                $counter = 1;
                while (Storage::exists('public/standar/' . $newFileName . '.' . $extension)) {
                    $newFileName = $originalName . '_' . $counter;
                    $counter++;
                }
    
                // Hapus file lama jika ada
                if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                    Storage::delete('public/' . $dokumen->file);
                }
    
                // Simpan file baru dengan nama unik
                $filePath = $file->storeAs('standar', $newFileName . '.' . $extension, 'public');
    
                // Tambahkan path file baru ke data yang akan diperbarui
                $updateData['file'] = $filePath;
            }
    
            // Update semua data sekaligus
            $dokumen->update($updateData);
    
            // Commit transaksi
            DB::commit();
    
            // Tampilkan pesan sukses
            Alert::success('Selesai', 'Dokumen berhasil diperbarui.');
            return redirect()->route('penetapan.standar');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }    

    public function destroy($id_standarinstitut)
    {
        try {
            // Ambil data dokumen berdasarkan ID, dengan relasi penetapan
            $dokumen = StandarInstitut::with('penetapan')->findOrFail($id_standarinstitut);

            if (!$dokumen) {
                Alert::error('Error', 'Dokumen tidak ditemukan.');
                return redirect()->route('penetapan.standar');
            }

            // Hapus file jika ada
            if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                Storage::delete('public/' . $dokumen->file);
            }

            // Hapus data dokumen_spmi
            $dokumen->delete();

            // Hapus data relasi penetapan
            if ($dokumen->penetapan) {
                $dokumen->penetapan->delete();
            }

            Alert::success('Selesai', 'Dokumen berhasil dihapus.');
            return redirect()->route('penetapan.standar');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
