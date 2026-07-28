<?php

use App\Http\Controllers\AuditInternalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenMutuController;
use App\Http\Controllers\EvaluasiDiriController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\StandarMutuController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// ── Public / Auth routes (from Breeze) ────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Redirect root to dashboard ────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('dashboard'));

// ── All authenticated routes ──────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Dokumen Mutu ──────────────────────────────────────────────────────────
    Route::resource('dokumen-mutu', DokumenMutuController::class);
    Route::get('/dokumen-mutu/{dokumenMutu}/download', [DokumenMutuController::class, 'download'])
        ->name('dokumen-mutu.download');

    // ── Standar Mutu ──────────────────────────────────────────────────────────
    Route::resource('standar-mutu', StandarMutuController::class);

    // ── Evaluasi Diri ─────────────────────────────────────────────────────────
    Route::resource('evaluasi-diri', EvaluasiDiriController::class);
    Route::post('/evaluasi-diri/{evaluasiDiri}/submit', [EvaluasiDiriController::class, 'submit'])
        ->name('evaluasi-diri.submit');
    Route::get('/evaluasi-diri/{evaluasiDiri}/download-bukti', [EvaluasiDiriController::class, 'downloadBukti'])
        ->name('evaluasi-diri.download-bukti');

    // ── Audit Internal ────────────────────────────────────────────────────────
    Route::prefix('audit-internal')->name('audit-internal.')->group(function () {
        Route::get('/', [AuditInternalController::class, 'index'])->name('index');
        Route::get('/{evaluasiDiri}', [AuditInternalController::class, 'show'])->name('show');

        // Auditor creates a temuan
        Route::get('/{evaluasiDiri}/temuan/create', [AuditInternalController::class, 'createTemuan'])
            ->name('temuan.create');
        Route::post('/{evaluasiDiri}/temuan', [AuditInternalController::class, 'storeTemuan'])
            ->name('temuan.store');

        // Auditee responds with CAPA
        Route::get('/finding/{auditMutu}/respond', [AuditInternalController::class, 'respondForm'])
            ->name('respond.form');
        Route::patch('/finding/{auditMutu}/respond', [AuditInternalController::class, 'respond'])
            ->name('respond');

        // Auditor closes a finding
        Route::patch('/finding/{auditMutu}/close', [AuditInternalController::class, 'close'])
            ->name('close');
    });

    // ── Admin-only: Manajemen Pengguna ────────────────────────────────────────
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::resource('fakultas', FakultasController::class);
        Route::resource('program-studi', ProgramStudiController::class);
    });
});
