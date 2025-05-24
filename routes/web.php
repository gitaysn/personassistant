<?php

use App\Http\Controllers\Admin\AlternatifController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataKriteriaController;
use App\Http\Controllers\Admin\HasilAkhirController;
use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\SubkriteriaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Landingpage\DressController;
use App\Http\Controllers\Landingpage\HomeController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/proses-rekomendasi', [HomeController::class, 'simpankuisionerdanrekomendasi'])->name('proses.rekomendasi');


// Jika user sudah login, arahkan ke dashboard
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('admin.pages.dashboard.index');
})->name('dashboard');

// Login & Register Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Menampilkan form lupa password
Route::get('/forgot-password', [\App\Http\Controllers\Admin\ForgotPasswordController::class, 'showForm'])->name('forgot.password.form');

// Mengirim email reset link
Route::post('/forgot-password', [\App\Http\Controllers\Admin\ForgotPasswordController::class, 'sendResetLink'])->name('forgot.password.send');

// Menampilkan form reset password (dari email)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Menyimpan password baru
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'indexPage'])->name('dashboard');

// Kriteria
Route::get('/kriteria', [DataKriteriaController::class, 'indexPage'])->name('admin.kriteria.index');
Route::get('/kriteria/create', [DataKriteriaController::class, 'create'])->name('admin.kriteria.create');
Route::post('/kriteria/store', [DataKriteriaController::class, 'store'])->name('admin.kriteria.store');
Route::get('/kriteria/{id}/edit', [DataKriteriaController::class, 'edit'])->name('admin.kriteria.edit');
Route::put('/kriteria/{id}/update', [DataKriteriaController::class, 'update'])->name('admin.kriteria.update');
Route::delete('/kriteria/{id}/delete', [DataKriteriaController::class, 'destroy'])->name('admin.kriteria.destroy');

// Sub Kriteria
Route::get('/admin/subkriteria/kriteria/{id}', [SubkriteriaController::class, 'showByKriteria'])->name('admin.pages.subkriteria.kriteria');
Route::get('/admin/subkriteria/kriteria/{id}/create', [SubkriteriaController::class, 'create'])->name('admin.subkriteria.create');
Route::post('/admin/subkriteria/store', [SubKriteriaController::class, 'store'])->name('admin.subkriteria.store');
Route::get('/admin/subkriteria/{id}/edit', [SubkriteriaController::class, 'edit'])->name('admin.subkriteria.edit');
Route::put('/admin/subkriteria/{id}', [SubkriteriaController::class, 'update'])->name('admin.pages.subkriteria.update');
Route::delete('/admin/subkriteria/{id}', [SubkriteriaController::class, 'destroy'])->name('subkriteria.destroy');

// Data alternatif
Route::get('/alternatif', [AlternatifController::class, 'indexPage'])->name('admin.alternatif.index');
Route::post('/admin/alternatif', [AlternatifController::class, 'store'])->name('admin.alternatif.store');       
Route::get('/alternatif/{id}/edit', [AlternatifController::class, 'edit'])->name('admin.alternatif.edit');
Route::put('/alternatif/{id}', [AlternatifController::class, 'update'])->name('admin.alternatif.update');
Route::delete('/alternatif/{id}', [AlternatifController::class, 'destroy'])->name('admin.alternatif.destroy');

// Penilaian alternatif
Route::get('/penilaian', [PenilaianController::class, 'indexPage'])->name('admin.penilaian.index');
Route::get('/penilaian/create', [PenilaianController::class, 'create'])->name('admin.penilaian.create');
Route::get('/penilaian/{id}/edit', [PenilaianController::class, 'edit'])->name('admin.penilaian.edit');
Route::put('/penilaian/{id}', [PenilaianController::class, 'update'])->name('admin.penilaian.update');

// Data user
// Route::get('/user', [UserController::class, 'index'])->name('users.index');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
// Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

// Data perhitungan
Route::get('/perhitungan/dress', [PerhitunganController::class, 'dress'])->name('admin.perhitungan.dress');
Route::get('/perhitungan/blouse', [PerhitunganController::class, 'blouse'])->name('admin.perhitungan.blouse');
Route::get('/perhitungan/cardigan', [PerhitunganController::class, 'cardigan'])->name('admin.perhitungan.cardigan');
Route::get('/perhitungan/rok', [PerhitunganController::class, 'rok'])->name('admin.perhitungan.rok');
Route::get('/perhitungan/celana', [PerhitunganController::class, 'celana'])->name('admin.perhitungan.celana');

// Riwayat
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('admin.riwayat.index');
Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('admin.riwayat.destroy');
