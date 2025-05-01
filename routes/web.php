<?php

// use Illuminate\Http\Request;
use App\Http\Controllers\Info;
use App\Http\Controllers\auth\Login;
// use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\Register;
use App\Http\Controllers\Panduanpengguna;
use App\Http\Controllers\standarController;
use App\Http\Controllers\timjamuController;
use App\Http\Controllers\evaluasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\perangkatController;
use App\Http\Controllers\peningkatanController;
use App\Http\Controllers\DataPenggunaController;
use App\Http\Controllers\LupaPasswordController;
use App\Http\Controllers\pelaksanaan1Controller;
use App\Http\Controllers\pelaksanaan2Controller;
use App\Http\Controllers\pengendalianController;
use App\Http\Controllers\ProfilPenggunaController;

// use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//  untuk user yang belum login (tamu), diarahkan ke halaman login
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [Login::class, 'index'])->name('auth.login');
    Route::post('/login', [Login::class, 'login'])->name('login');
    Route::get('/PanduanPengguna', [Panduanpengguna::class, 'index'])->name('FilePanduanPangguna');
    Route::get('/Info', [Info::class, 'index'])->name('info');

    // Route::get('/Registrasi-akun-pengguna', [Register::class, 'create'])->name('auth.register');
    // Route::post('/Registrasi', [Register::class, 'store'])->name('simpanDataRegistrasi');
});


// Tampilkan form lupa password
Route::get('/lupa-password', [LupaPasswordController::class, 'index'])->name('lupa-password');

// Proses pencarian akun
Route::post('/lupa-password/cari', [LupaPasswordController::class, 'cariAkun'])->name('lupa-password.cari');

// Tampilkan form ganti password
// Route::get('/password-baru/{id}', [LupaPasswordController::class, 'passwordbaruForm'])->name('password-baru.form');
Route::get('/buat-password-baru', function () {
    return view('auth.password-baru');
})->name('password-baru.form');

// Simpan password baru
Route::post('/password-baru/{id}', [LupaPasswordController::class, 'passwordBaru'])->name('password-baru.update');


//  untuk user yang sudah login, diarahkan ke halaman BerandaSIJAMUFKIP
Route::middleware(['cekLogin'])->group(function () {
    Route::get('/', function () {
        return view('User.admin.index');
    });

    // Profil Akun Pengguna
    Route::get('/profilpengguna/edit', [ProfilPenggunaController::class, 'profilpengguna'])->name('profilpengguna.edit');
    Route::put('/profilpengguna/update', [ProfilPenggunaController::class, 'updateProfil'])->name('profilpengguna.update');

    // Pengaturan
    Route::get('/pengaturan', [ProfilPenggunaController::class, 'pengaturan'])->name('pengaturan');
    // Halaman yang ada menu Ubah Password
    Route::get('/pengguna/pengaturan/form-ubah-password', [ProfilPenggunaController::class, 'formUbahPassword'])->name('pengguna.form-ubah-password'); //edit
    Route::post('/pengguna/pengaturan/ubah-password', [ProfilPenggunaController::class, 'updatePassword'])->name('pengguna.ubah-password'); //update
    Route::put('/profil/reset-password', [ProfilPenggunaController::class, 'resetPasswordToNIP'])->name('pengguna.reset-password');
    Route::get('/logout', [Login::class, 'logout'])->name('logout');
    // Route::get('/send-email', [EmailController::class, 'sendEmail']);


    // route untuk halaman menu Home atau dashboard
    Route::get('/Beranda', [DashboardController::class, 'index'])->name('BerandaSIJAMUFKIP');

    //route untuk halaman Data Pengguna
    Route::get('/DataPengguna', [DataPenggunaController::class, 'index'])->name('DataPengguna');
    Route::get('/DataPengguna/tambahDataPengguna', [DataPenggunaController::class, 'create'])->name('tambahDataPengguna');
    Route::post('/DataPengguna', [DataPenggunaController::class, 'store'])->name('simpanDataPengguna');
    Route::get('/DataPengguna/editDataPengguna/{id}', [DataPenggunaController::class, 'edit'])->name('editDataPengguna');
    Route::delete('/DataPengguna/{id}', [DataPenggunaController::class, 'destroy'])->name('hapusDataPengguna');
    Route::put('/DataPengguna/{id}/updateDataPengguna', [DataPenggunaController::class, 'update'])->name('updateDataPengguna');


    // route untuk halaman menu Tim Penjaminan Mutu CRUD
    Route::get('/TimPenjaminanMutu', [timjamuController::class, 'index'])->name('TimJAMU');
    Route::get('/TimPenjaminanMutu/tambahTimJAMU', [timjamuController::class, 'create'])->name('tambahTimJAMU');
    Route::get('/TimPenjaminanMutu/editTimJAMU/{id}', [timjamuController::class, 'edit'])->name('editTimJAMU');
    Route::post('/TimPenjaminanMutu', [timjamuController::class, 'store'])->name('jamutims.store');
    Route::delete('/TimPenjaminanMutu/{id}', [timjamuController::class, 'destroy'])->name('hapusTimJAMU');
    Route::put('/TimPenjaminanMutu/{id}/updateTimJAMU', [timjamuController::class, 'update'])->name('updateTimJAMU');

    // route untuk halaman menu Penetapan CRUD -> Dokumen Perangkat SPMI
    Route::get('/Penetapan/DokumenSPMI', [perangkatController::class, 'index'])->name('penetapan.perangkat');
    Route::get('/Penetapan/tambahDokumenPerangkatSPMI', [perangkatController::class, 'create'])->name('tambahDokumenPerangkat');
    Route::get('/Penetapan/editDokumenPerangkatSPMI/{id_dokspmi}', [perangkatController::class, 'edit'])->name('editDokumenPerangkat');
    Route::resource('/tambahDokumenPerangkatSPMI-2', perangkatController::class);
    Route::get('/dokumenPerangkatSPMI/{id_dokspmi}/buka/{namafile}', [perangkatController::class, 'lihatdokumenperangkat'])->name('dokumenperangkat.tampil');
    Route::delete('/Penetapan/PerangkatSPMI{id_dokspmi}', [perangkatController::class, 'destroy'])->name('hapusDokumenPerangkat');
    Route::put('Penetapan/updateDokumenPerangkat/{id_dokspmi}', [perangkatController::class, 'update'])->name('updateDokumenPerangkat');

    // route untuk halaman menu Penetapan CRUD -> Standar Yang Ditetapkan Institusi
    Route::get('/Penetapan/StandarInstitusi', [standarController::class, 'index'])->name('penetapan.standar');
    // Route::get('/Penetapan/unggahDokumenStandarSPMI/{id}', [standarController::class, 'create'])->name('unggahDokumenStandar');
    Route::get('/Penetapan/tambahDokumenStandarSPMI', [standarController::class, 'create'])->name('tambahStandar'); //tambah dokumen standar
    Route::resource('/tambahDokumenStandar-2', standarController::class);

    // Route::post('/StandarYangDitetapkanInstitusi', [standarController::class, 'store'])->name('standar.store');
    Route::get('/Penetapan/editDokumenStandarSPMI/{id_standarinstitut}', [standarController::class, 'edit'])->name('editDataStandar');
    // Route::post('/unggahDokumenStandarSPMI', [standarController::class, 'uploadDokumen']);
    Route::get('/dokumenStandarInstitusi/{id_standarinstitut}/buka/{namafile}', [standarController::class, 'lihatdokumenstandar'])->name('dokumenstandar.tampil');
    Route::delete('/Penetapan/StandarSPMI{id_standarinstitut}', [standarController::class, 'destroy'])->name('hapusDokumenStandar');
    Route::put('Penetapan/updateDokumenStandar/{id_standarinstitut}', [standarController::class, 'update'])->name('updateDokumenStandar');
    // Route::get('/Penetapan/StandarInstitusi/folder/{id}', [standarController::class, 'folder'])->name('FolderDokumenStandar');


    // route untuk halaman menu Pelaksanaan CRUD
    Route::get('/Pelaksanaan/Prodi', [pelaksanaan1Controller::class, 'index'])->name('pelaksanaan.prodi');
    Route::get('/tambahdata-dokumen-pelaksanaan-prodi', [pelaksanaan1Controller::class, 'create'])->name('tambahPelaksanaanProdi');
    Route::get('/tambahdata-pelaksanaan-prodi-formulirkepuasanmhs', [pelaksanaan1Controller::class, 'create_FormKepuasanMhs'])->name('tambahPelaksanaanProdi-Form');
    Route::resource('/simpanPelaksanaanProdi', pelaksanaan1Controller::class);
    Route::get('/Pelaksanaan/Prodi/editPelaksanaanProdi/{id_plks_prodi}', [pelaksanaan1Controller::class, 'edit'])->name('editPelaksanaanProdi');
    Route::get('/Pelaksanaan/Prodi/editPelaksanaanProdi/FormulirKepuasanMahasiswa/{id_plks_prodi}', [pelaksanaan1Controller::class, 'edit_FormKepuasanMhs'])->name('editPelaksanaanProdi-Form');
    Route::get('/dokumen/pelaksanaanprodi/{id_plks_prodi}/buka/{namafile}', [pelaksanaan1Controller::class, 'lihatdokumenPlksProdi'])->name('dokumenpelaksanaanProdi.tampil');
    Route::put('Pelaksanaan/Prodi/update-dokumen-pelaksanaan/{id_plks_prodi}', [pelaksanaan1Controller::class, 'update'])->name('updatePelaksanaanProdi');
    Route::delete('/hapus-dokumen-pelaksanaan{id_plks_prodi}', [pelaksanaan1Controller::class, 'destroy'])->name('deletePelaksanaanProdi');

    Route::get('/Pelaksanaan/Fakultas', [pelaksanaan2Controller::class, 'index'])->name('pelaksanaan.fakultas');
    Route::get('/tambahdata-dokumen-pelaksanaan-fakultas', [pelaksanaan2Controller::class, 'create'])->name('tambahPelaksanaanFakultas');
    Route::resource('/simpanPelaksanaanFakultas', pelaksanaan2Controller::class);
    Route::get('/Pelaksanaan/Fakultas/editPelaksanaanFakultas/{id_plks_fklts}', [pelaksanaan2Controller::class, 'edit'])->name('editPelaksanaanFakultas');
    Route::get('/dokumen/pelaksanaanfakultas/{id_plks_fklts}/buka/{namafile}', [pelaksanaan2Controller::class, 'lihatdokumenPlksFakultas'])->name('dokumenpelaksanaanFakultas.tampil');
    Route::put('Pelaksanaan/Fakultas/update-dokumen-pelaksanaan-fakultas/{id_plks_fklts}', [pelaksanaan2Controller::class, 'update'])->name('updatePelaksanaanFakultas');
    Route::delete('/hapus-dokumen-pelaksanaan-fakultas/{id_plks_fklts}', [pelaksanaan2Controller::class, 'destroy'])->name('deletePelaksanaanFakultas');


    // route untuk halaman menu Evaluasi CRUD
    Route::get('/Evaluasi/AuditMutuInternal', [evaluasiController::class, 'index'])->name('evaluasi');
    Route::get('/Evaluasi/tambahDokumenEvaluasi', [evaluasiController::class, 'create'])->name('tambahDokumenAMI');
    Route::resource('/tambahDokumenEvaluasi-2', evaluasiController::class);
    Route::get('/dokumenEvaluasi/{id_dokumeneval}/buka/{nama_dokumen}', [evaluasiController::class, 'lihatdokumenevaluasi'])->name('dokumenevaluasi.tampil');
    Route::delete('/Evaluasi/hapusEvaluasi{id_evaluasi}', [evaluasiController::class, 'destroy'])->name('hapusDokumenEvaluasi');
    Route::get('/Evaluasi/editDokumenEvaluasi/{id_evaluasi}', [evaluasiController::class, 'edit'])->name('editDokumenEvaluasi');
    Route::put('Evaluasi/updateDokumenEvaluasi/{id_evaluasi}', [evaluasiController::class, 'update'])->name('updateDokumenEvaluasi');

    // route untuk halaman menu Pengendalian CRUD
    Route::get('/Pengendalian/Standar/RTM', [pengendalianController::class, 'index'])->name('pengendalian');
    Route::get('/Pengendalian/tambahDokumenPengendalian', [pengendalianController::class, 'create'])->name('tambahDokumenPengendalian');
    Route::resource('/tambahDokumenPengendalian-2', pengendalianController::class);
    Route::get('/dokumenPengendalian/{id_pengendalian}/buka/{jenis_file}', [pengendalianController::class, 'lihatdokumenpengendalian'])->name('dokumenpengendalian.tampil');
    Route::delete('/Pengendalian/hapusPengendalian{id_pengendalian}', [pengendalianController::class, 'destroy'])->name('hapusDokumenPengendalian');
    Route::get('/Pengendalian/editDokumenPengendalian/{id_pengendalian}', [pengendalianController::class, 'edit'])->name('editDokumenPengendalian');
    Route::put('Pengendalian/updateDokumenPengendalian/{id_pengendalian}', [pengendalianController::class, 'update'])->name('updateDokumenPengendalian');

    // route untuk halaman menu Peningkatan CRUD
    Route::get('Peningkatan/StandarInstitusi', [peningkatanController::class, 'index'])->name('peningkatan');
    Route::get('/Peningkatan/tambahDokumenPeningkatan', [peningkatanController::class, 'create'])->name('tambahDokumenPeningkatan');
    Route::resource('/tambahDokumenPeningkatan-2', peningkatanController::class);
    Route::get('/dokumenPeningkatan/{id_peningkatan}/buka/{nama_dokumen}', [peningkatanController::class, 'lihatdokumenpeningkatan'])->name('dokumenpeningkatan.tampil');
    Route::delete('/Peningkatan/hapusPeningkatan{id_peningkatan}', [peningkatanController::class, 'destroy'])->name('hapusDokumenPeningkatan');
    Route::get('/Peningkatan/editDokumenPeningkatan/{id_peningkatan}', [peningkatanController::class, 'edit'])->name('editDokumenPeningkatan');
    Route::put('Peningkatan/updateDokumenPeningkatan/{id_peningkatan}', [peningkatanController::class, 'update'])->name('updateDokumenPeningkatan');
});
