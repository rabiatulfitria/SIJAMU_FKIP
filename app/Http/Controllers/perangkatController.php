<?php

namespace App\Http\Controllers;

use App\Models\Penetapan;
use App\Models\NamaFileP1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Console\StorageLinkCommand;
use Illuminate\Support\Facades\Mail;

class perangkatController extends Controller
{
    public function index()
    {
        $dokumenp1 = DB::table('dokumen_spmi')
            ->join('penetapans', 'dokumen_spmi.id_penetapan', '=', 'penetapans.id_penetapan')
            ->select(
                'dokumen_spmi.id_dokspmi',
                'penetapans.id_penetapan as id_penetapan',
                'dokumen_spmi.namafile as nama_dokumenspmi',
                'dokumen_spmi.kategori as kategori',
                'penetapans.tanggal_ditetapkan as tanggal_ditetapkan',
                'dokumen_spmi.file as unggahan_dokumen' //unggahan_dokumen sebagai nama di $row
            )
            ->get();

        return view('User.admin.Penetapan.perangkatspmi', compact('dokumenp1'));
    }


    public function create()  //tombol Tambah
    {
        $penetapans = DB::table('penetapans')->select('id_penetapan', 'tanggal_ditetapkan')->get();

        // Mengirim data ke view
        return view('User.admin.Penetapan.tambah_perangkatspmi', compact('penetapans'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_dokumenspmi' => 'required|string|max:255', //nama dari models DokumenSPMI
            'kategori' => 'required|string',
            'tanggal_ditetapkan' => 'nullable|date',
            'files' => 'required',
            'files.*' => 'file|mimes:pdf,doc,docx,xlsx,png,jpg,jpeg|max:5120' //Maksimum 5120 KB (5 MB)
        ]);

        // Simpan data ke tabel penetapans menggunakan query builder
        $idPenetapan = DB::table('penetapans')->insertGetId([
            'tanggal_ditetapkan' => $validatedData['tanggal_ditetapkan'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengupload file dan simpan ke tabel dokumen_spmi
        try {
            DB::beginTransaction();

            foreach ($request->file('files') as $file) {
                // Generate unique file name
                $namaDokumen = time() . '-' . $file->getClientOriginalName();

                // Store file in the 'public/perangkatspmi' directory
                $path = $file->storeAs('perangkatspmi', $namaDokumen, 'public');

                // Insert data into 'dokumen_spmi' table
                DB::table('dokumen_spmi')->insert([
                    'id_penetapan' => $idPenetapan,
                    'namafile' => $request->nama_dokumenspmi,
                    'kategori' => $request->kategori,
                    'file' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
    
            DB::commit();
    
            // Fetch all user emails except the authenticated user
            $emails = DB::table('users')
                ->where('email', '!=', auth()->user()->email)
                ->pluck('email');

            $hasFiles = $request->hasFile('files'); // Cek apakah ada file dalam request

            // Prepare email details
            $emailDetails = [
                'subject' => 'Pemberitahuan Dokumen Perangkat SPMI',
                'body' => $hasFiles 
                    ? 'Ada Dokumen Perangkat SPMI Yang Dikirim Beserta File Dokumen.' 
                    : 'Ada Dokumen Perangkat SPMI Yang Dikirim Tanpa File Dokumen.'
            ];
    
            // Send email to each user
            foreach ($emails as $email) {
                Mail::raw($emailDetails['body'], function ($message) use ($emailDetails, $email) {
                    $message->to($email)
                        ->subject($emailDetails['subject']);
                });
            }
    
            Alert::success('Selesai', 'Dokumen berhasil ditambahkan dan email telah dikirim.');
            return redirect()->route('penetapan.perangkat');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function lihatdokumenperangkat($id_penetapan)
    {
        $dokumenp1 = Penetapan::findOrFail($id_penetapan);
        $filePaths = json_decode($dokumenp1->files, true);

        if (is_array($filePaths) && !empty($filePaths)) {
            $file = $filePaths[0];

            if (Storage::disk('local')->exists($file)) {
                return response()->file(storage_path('app' . $file));
            } else {
                abort(404, 'File not found.');
            }
        }
    }

    public function edit(String $id_dokspmi)
    {
        // Ambil data dokumen_spmi yang ingin diedit
        $dokumenp1 = DB::table('dokumen_spmi')
            ->join('penetapans', 'dokumen_spmi.id_penetapan', '=', 'penetapans.id_penetapan')
            ->select(
                'dokumen_spmi.id_dokspmi',
                'penetapans.id_penetapan as id_penetapan',
                'dokumen_spmi.namafile as nama_dokumenspmi',
                'dokumen_spmi.kategori as kategori',
                'penetapans.tanggal_ditetapkan as tanggal_ditetapkan',
                'dokumen_spmi.file as unggahan_dokumen'
            )
            ->where('dokumen_spmi.id_dokspmi', '=', $id_dokspmi)
            ->first();
        return view('User.admin.Penetapan.edit_perangkatspmi', ['oldData' => $dokumenp1]);

    }

    public function update(Request $request, $id_dokspmi)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_dokumenspmi' => 'required|string|max:255',
            'kategori' => 'required|string',
            'tanggal_ditetapkan' => 'nullable|date',
            'files.*' => 'file|mimes:pdf,doc,docx,xlsx,png,jpg,jpeg|max:5120' //Maksimum 5120 KB (5 MB)
            //ini yang dipakai sebagai properti dari variabel $oldData
            // ex: $oldData->nama_dokumenspmi
        ]);

        try {
            DB::beginTransaction();

            // Ambil data lama dari tabel
            $dokumen = DB::table('dokumen_spmi')->where('id_dokspmi', $id_dokspmi)->first();

            // Update tabel dokumen_spmi
            DB::table('dokumen_spmi')->where('id_dokspmi', $id_dokspmi)->update([
                'namafile' => $validatedData['nama_dokumenspmi'],
                'kategori' => $validatedData['kategori'],
                'updated_at' => now(),
            ]);

            // Update tabel penetapans
            DB::table('penetapans')->where('id_penetapan', $dokumen->id_penetapan)->update([
                'tanggal_ditetapkan' => $validatedData['tanggal_ditetapkan'],
                'updated_at' => now(),
            ]);

            // Jika ada file baru, hapus file lama dan simpan file baru
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Hapus file lama jika ada
                    if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                        Storage::delete('public/' . $dokumen->file);
                    }

                    // Simpan file baru
                    $namaFile = time() . '-' . $file->getClientOriginalName();
                    $path = $file->storeAs('perangkatspmi', $namaFile, 'public');

                    // Update path file baru di tabel dokumen_spmi
                    DB::table('dokumen_spmi')->where('id_dokspmi', $id_dokspmi)->update(['file' => $path]);
                }
            }

            DB::commit();
            Alert::success('Selesai', 'Dokumen berhasil diperbarui.');
            return redirect()->route('penetapan.perangkat');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy(String $id_dokspmi)
    {
        try {
            // Ambil data dokumen berdasarkan id
            $dokumen = DB::table('dokumen_spmi')->where('id_dokspmi', $id_dokspmi)->first();

            // Pastikan data dokumen ditemukan
            if ($dokumen) {
                // Hapus file dari storage
                if ($dokumen->file && Storage::exists('public/' . $dokumen->file)) {
                    Storage::delete('public/' . $dokumen->file);
                }

                // Hapus data dari tabel dokumen_spmi
                DB::table('dokumen_spmi')->where('id_dokspmi', $id_dokspmi)->delete();


                Alert::success('Selesai', 'Dokumen berhasil dihapus.');
                return redirect()->route('penetapan.perangkat');
            } else {

                Alert::error('error', 'Dokumen gagal dihapus.');
                return redirect()->route('penetapan.perangkat');
            }
        } catch (\Exception $e) {

            Alert::error('error', 'Dokumen gagal dihapus.');
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
