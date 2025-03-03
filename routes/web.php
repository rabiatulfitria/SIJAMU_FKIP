<?php

use App\Http\Controllers\Info;
use App\Http\Controllers\auth\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\Register;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Panduanpengguna;
use App\Http\Controllers\standarController;
use App\Http\Controllers\timjamuController;
use App\Http\Controllers\evaluasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\perangkatController;
use App\Http\Controllers\peningkatanController;
use App\Http\Controllers\DataPenggunaController;
use App\Http\Controllers\pelaksanaan1Controller;
use App\Http\Controllers\pelaksanaan2Controller;
use App\Http\Controllers\pengendalianController;

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

//AUTH
    Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [Login::class, 'index'])->name('auth.login');
    Route::post('/login', [Login::class, 'login'])->name('login');
});

    Route::get('/logout', [Login::class, 'logout'])->name('logout');
    Route::get('/send-email', [EmailController::class, 'sendEmail']);

// Grup route dengan middleware 'ceklogin'
    Route::middleware(['cekLogin'])->group(function () {
    Route::get('/', function () {
        return view('User.admin.index');
    });
    
    // route untuk menu Sign Up, Unduh Panduan Pengguna, Info
    Route::get('/Sign Up', [Register::class, 'index'])->name('form-signup');
    Route::get('/PanduanPengguna', [Panduanpengguna::class, 'index'])->name('FilePanduanPangguna');
    Route::get('/Info', [Info::class, 'index'])->name('info');

    // route untuk halaman menu Home atau dashboard
    Route::get('/Beranda', [DashboardController::class, 'index'])->name('BerandaSIJAMUFIP');

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
    Route::get('/Penetapan/tambahDokumenPerangkatSPMI',[perangkatController::class, 'create'])->name('tambahDokumenPerangkat');
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
    Route::get('/tambah-dokumen-pelaksanaan-prodi', [pelaksanaan1Controller::class, 'create'])->name('tambahPelaksanaanProdi');
    Route::resource('/simpanPelaksanaanProdi', pelaksanaan1Controller::class);
    Route::get('/Pelaksanaan/Prodi/editPelaksanaanProdi/{id_plks_prodi}', [pelaksanaan1Controller::class, 'edit'])->name('editPelaksanaanProdi');
    Route::get('/dokumen/pelaksanaanprodi/{id_plks_prodi}/buka/{namafile}', [pelaksanaan1Controller::class, 'lihatdokumenPlksProdi'])->name('dokumenpelaksanaanProdi.tampil');
    Route::put('Pelaksanaan/Prodi/update-dokumen-pelaksanaan/{id_plks_prodi}', [pelaksanaan1Controller::class, 'update'])->name('updatePelaksanaanProdi');
    Route::delete('/hapus-dokumen-pelaksanaan{id_plks_prodi}', [pelaksanaan1Controller::class, 'destroy'])->name('deletePelaksanaanProdi');

    Route::get('/Pelaksanaan/Fakultas', [pelaksanaan2Controller::class, 'index'])->name('pelaksanaan.fakultas');
    Route::get('/tambah-dokumen-pelaksanaan-fakultas', [pelaksanaan2Controller::class, 'create'])->name('tambahPelaksanaanFakultas');
    Route::resource('/simpanPelaksanaanFakultas', pelaksanaan2Controller::class);
    Route::get('/Pelaksanaan/Fakultas/editPelaksanaanFakultas/{id_plks_fklts}', [pelaksanaan2Controller::class, 'edit'])->name('editPelaksanaanFakultas');
    Route::get('/dokumen/pelaksanaanfakultas/{id_plks_fklts}/buka/{namafile}', [pelaksanaan2Controller::class, 'lihatdokumenPlksFakultas'])->name('dokumenpelaksanaanFakultas.tampil');
    Route::put('Pelaksanaan/Fakultas/update-dokumen-pelaksanaan-fakultas/{id_plks_fklts}', [pelaksanaan2Controller::class, 'update'])->name('updatePelaksanaanFakultas');
    Route::delete('/hapus-dokumen-pelaksanaan-fakultas/{id_plks_fklts}', [pelaksanaan2Controller::class, 'destroy'])->name('deletePelaksanaanFakultas');


    // route untuk halaman menu Evaluasi CRUD
    Route::get('/Evaluasi/AuditMutuInternal',[evaluasiController::class, 'index'])->name('evaluasi');
    Route::get('/Evaluasi/tambahDokumenEvaluasi',[evaluasiController::class, 'create'])->name('tambahDokumenAMI');
    Route::resource('/tambahDokumenEvaluasi-2', evaluasiController::class);
    Route::get('/dokumenEvaluasi/{id_feval}/buka/{nama_fileeval}', [evaluasiController::class, 'lihatdokumenevaluasi'])->name('dokumenevaluasi.tampil');
    Route::delete('/Evaluasi/PerangkatSPMI{id_evaluasi}', [evaluasiController::class, 'destroy'])->name('hapusDokumenEvaluasi');
    Route::get('/Evaluasi/editDokumenPerangkatSPMI/{id_evaluasi}', [evaluasiController::class, 'edit'])->name('editDokumenEvaluasi');
    Route::put('Evaluasi/updateDokumenEvaluasi/{id_evaluasi}', [evaluasiController::class, 'update'])->name('updateDokumenEvaluasi');

    // route untuk halaman menu Pengendalian CRUD
    Route::get('/Pengendalian/Standar/RTM',[pengendalianController::class, 'index'])->name('pengendalian');
    Route::get('/Pengendalian/tambahDokumenPengendalian',[pengendalianController::class, 'create'])->name('tambahDokumenPengendalian');
    Route::resource('/tambahDokumenPengendalian-2', pengendalianController::class);
    Route::get('/dokumenPengendalian/{id_pengendalian}/buka/{jenis_file}', [pengendalianController::class, 'lihatdokumenpengendalian'])->name('dokumenpengendalian.tampil');
    Route::delete('/Pengendalian/hapusPengendalian{id_pengendalian}', [pengendalianController::class, 'destroy'])->name('hapusDokumenPengendalian');
    Route::get('/Pengendalian/editDokumenPengendalian/{id_pengendalian}', [pengendalianController::class, 'edit'])->name('editDokumenPengendalian');
    Route::put('Pengendalian/updateDokumenPengendalian/{id_pengendalian}', [pengendalianController::class, 'update'])->name('updateDokumenPengendalian');

    // route untuk halaman menu Peningkatan CRUD
    Route::get('Peningkatan/StandarInstitusi',[peningkatanController::class, 'index'])->name('peningkatan');
    Route::get('/Peningkatan/tambahDokumenPeningkatan',[peningkatanController::class, 'create'])->name('tambahDokumenPeningkatan');
    Route::resource('/tambahDokumenPeningkatan-2', peningkatanController::class);
    Route::get('/dokumenPeningkatan({id_peningkatan})',[peningkatanController::class, 'lihatdokumenpeningkatan'])->name('dokumenpeningkatan.tampil');
    Route::delete('/Peningkatan/hapusPeningkatan{id_peningkatan}', [peningkatanController::class, 'destroy'])->name('hapusDokumenPeningkatan');
    Route::get('/Peningkatan/editDokumenPeningkatan/{id_peningkatan}', [peningkatanController::class, 'edit'])->name('editDokumenPeningkatan');
    Route::put('Peningkatan/updateDokumenPeningkatan/{id_peningkatan}', [peningkatanController::class, 'update'])->name('updateDokumenPeningkatan');

});
