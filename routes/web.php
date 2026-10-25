<?php

use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Pasien\PoliController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Pasien\PembayaranController as PasienPembayaranController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

    Route::get('/export/dokter', [DokterController::class, 'exportExcel'])->name('admin.dokter.export');
    Route::get('/export/pasien', [PasienController::class, 'exportExcel'])->name('admin.pasien.export');
    Route::get('/export/obat', [ObatController::class, 'exportExcel'])->name('admin.obat.export');

    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::post('/pembayaran/{id}/verify', [AdminPembayaranController::class, 'verify'])->name('admin.pembayaran.verify');
});

// DOKTER ROUTES
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    Route::get('/export/jadwal-periksa', [JadwalPeriksaController::class, 'exportExcel'])->name('dokter.jadwal-periksa.export');

    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');

    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
    Route::get('/export/riwayat-pasien', [RiwayatPasienController::class, 'exportExcel'])->name('dokter.riwayat-pasien.export');
});

// PASIEN ROUTES
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');
    Route::get('/live-update', [PasienDashboardController::class, 'getLiveUpdate'])->name('pasien.live-update');

    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');

    Route::get('/riwayat-pendaftaran', [RiwayatPendaftaranController::class,'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat-pendaftaran/{id}', [RiwayatPendaftaranController::class,'show'])->name('pasien.riwayat.show');

    Route::get('/pembayaran', [PasienPembayaranController::class, 'index'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{id}/upload', [PasienPembayaranController::class, 'upload'])->name('pasien.pembayaran.upload');
});
