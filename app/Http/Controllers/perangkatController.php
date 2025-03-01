<?php

namespace App\Http\Controllers;

use App\Models\Penetapan;
use App\Models\DokumenSPMI;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PerangkatController extends Controller
{
    public function index()
    {
        $dokumenp1 = DokumenSPMI::with('penetapan')
            ->select('id_dokspmi', 'id_penetapan', 'namafile', 'kategori', 'file')
            ->get();
        // dd($dokumenp1);    

        return view('User.admin.Penetapan.perangkatspmi', compact('dokumenp1'));
    }

    public function create()
    {
        $penetapans = Penetapan::select('id_penetapan', 'tanggal_ditetapkan')->get();
        return view('User.admin.Penetapan.tambah_perangkatspmi', compact('penetapans'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'namafile' => 'required|string|max:255',
            'kategori' => 'required|string',
            'tanggal_ditetapkan' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480'
        ]);

        DB::beginTransaction();
        try {
            $penetapans = Penetapan::create(['tanggal_ditetapkan' => $validatedData['tanggal_ditetapkan']]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->storeAs('perangkatspmi', $file->getClientOriginalName(), 'public');

                DokumenSPMI::create([
                    'id_penetapan' => $penetapans->id_penetapan,
                    'namafile' => $validatedData['namafile'],
                    'kategori' => $validatedData['kategori'],
                    'file' => $path
                ]);
            }



            // $emails = User::where('email', '!=', auth()->user()->email)->pluck('email');
            // $emailBody = $request->hasFile('file') ?
            //     'Ada Dokumen Perangkat SPMI Yang Dikirim Beserta File Dokumen.' :
            //     'Ada Dokumen Perangkat SPMI Yang Dikirim Tanpa File Dokumen.';

            // foreach ($emails as $email) {
            //     Mail::raw($emailBody, function ($message) use ($email) {
            //         $message->to($email)->subject('Pemberitahuan Dokumen Perangkat SPMI');
            //     });
            // }

            DB::commit();
            Alert::success('Selesai', 'Dokumen berhasil ditambahkan dan email telah dikirim.');
            return redirect()->route('penetapan.perangkat');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function lihatdokumenperangkat($id_dokspmi)
    {
        $dokumenp1 = DokumenSPMI::findOrFail($id_dokspmi);
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


        // Untuk PDF & gambar, tampilkan langsung di browser
        return response()->file($filePath, [
            'Content-Type' => $mimeTypes[$fileExtension],
            'Content-Disposition' => 'inline; filename="' . $dokumenp1->namafile . '.' . $fileExtension . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }


    public function edit($id_dokspmi)
    {
        $dokumenp1 = DokumenSPMI::with('penetapan')->findOrFail($id_dokspmi);
        return view('User.admin.Penetapan.edit_perangkatspmi', ['oldData' => $dokumenp1]);
    }

    public function update(Request $request, $id_dokspmi)
    {
        $validatedData = $request->validate([
            'namafile' => 'required|string|max:255',
            'kategori' => 'required|string',
            'tanggal_ditetapkan' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:20480'
        ]);

        DB::beginTransaction();
        try {
            $dokumen = DokumenSPMI::findOrFail($id_dokspmi);
            $dokumen->update(['namafile' => $validatedData['namafile'], 'kategori' => $validatedData['kategori']]);
            $dokumen->penetapan()->update(['tanggal_ditetapkan' => $validatedData['tanggal_ditetapkan']]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                    Storage::delete('public/' . $dokumen->file);
                }
                $path = $file->storeAs('perangkatspmi', $file->getClientOriginalName(), 'public');
                $dokumen->update(['file' => $path]);
            }

            DB::commit();
            Alert::success('Selesai', 'Dokumen berhasil diperbarui.');
            return redirect()->route('penetapan.perangkat');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy(String $id_dokspmi)
    {
        try {
            // Ambil data dokumen berdasarkan ID, dengan relasi penetapan
            $dokumen = DokumenSPMI::with('penetapan')->findOrFail($id_dokspmi);

            if (!$dokumen) {
                Alert::error('Error', 'Dokumen tidak ditemukan.');
                return redirect()->route('penetapan.perangkat');
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
            return redirect()->route('penetapan.perangkat');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
