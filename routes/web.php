<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ParameterController;
use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\Admin\HasilAnalisisController as AdminHasilAnalisis;
use App\Http\Controllers\Analis\DashboardController as AnalisDashboard;
use App\Http\Controllers\Analis\AnalisisController;
use App\Http\Controllers\KepCab\DashboardController as KepCabDashboard;
use App\Http\Controllers\KepCab\ApprovalController;
use App\Http\Controllers\Marketing\DashboardController as MarketingDashboard;
use App\Http\Controllers\Marketing\HasilAnalisisController as MarketingHasil;

// Auth 
Route::middleware('guest')->group(function () {
    Route::get('/',          fn() => redirect()->route('login'));
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin 
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::resource('parameter', ParameterController::class);
    Route::resource('rules', RuleController::class);

    Route::get   ('setting-konversi', [App\Http\Controllers\Admin\SettingKonversiController::class, 'index'])->name('setting-konversi.index');
    Route::post  ('setting-konversi', [App\Http\Controllers\Admin\SettingKonversiController::class, 'update'])->name('setting-konversi.update');

    Route::get   ('analisis',                    [AdminHasilAnalisis::class, 'index'])->name('analisis.index');
    Route::get   ('analisis/{analisis}',         [AdminHasilAnalisis::class, 'show'])->name('analisis.show');
    Route::get   ('analisis/{analisis}/pdf',     [AdminHasilAnalisis::class, 'exportPdf'])->name('analisis.pdf');
    Route::delete('analisis/{analisis}',         [AdminHasilAnalisis::class, 'destroy'])->name('analisis.destroy');
});

// Kredit Analis
Route::prefix('analis')->name('analis.')->middleware(['auth', 'role:analis'])->group(function () {
    Route::get ('dashboard',                     [AnalisDashboard::class, 'index'])->name('dashboard');

    Route::get ('analisis',                      [AnalisisController::class, 'index'])->name('analisis.index');
    Route::get ('analisis/baru',                 [AnalisisController::class, 'create'])->name('analisis.create');
    Route::post('analisis',                      [AnalisisController::class, 'store'])->name('store');

    // Detail & PDF
    Route::get ('analisis/{analisis}',           [AnalisisController::class, 'show'])->name('analisis.show');
    Route::get ('analisis/{analisis}/pdf',       [AnalisisController::class, 'exportPdf'])->name('analisis.pdf');
});

// Kepala Cabang
Route::prefix('kepala-cabang')->name('kepala_cabang.')->middleware(['auth', 'role:kepala_cabang'])->group(function () {
    Route::get('dashboard', [KepCabDashboard::class, 'index'])->name('dashboard');

    Route::get  ('approval',                          [ApprovalController::class, 'index'])->name('approval.index');
    Route::get  ('approval/{approval}',               [ApprovalController::class, 'show'])->name('approval.show');
    Route::post ('approval/{approval}/approve',       [ApprovalController::class, 'approve'])->name('approval.approve');
});

// Marketing
Route::prefix('marketing')->name('marketing.')->middleware(['auth', 'role:marketing'])->group(function () {
    Route::get('dashboard',              [MarketingDashboard::class, 'index'])->name('dashboard');
    Route::get('analisis',               [MarketingHasil::class, 'index'])->name('analisis.index');
    Route::get('analisis/{analisis}',    [MarketingHasil::class, 'show'])->name('analisis.show');
});
