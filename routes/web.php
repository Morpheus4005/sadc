<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\PeriodeController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome-sadc');
})->name('home');

// Auth routes (tanpa prefix untuk login/register)
require __DIR__.'/auth.php';

// Main application dengan prefix "recap-sadc"
Route::prefix('recap-sadc')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Periode Management
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::get('/periode/create', [PeriodeController::class, 'create'])->name('periode.create');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::post('/periode/switch', [PeriodeController::class, 'switch'])->name('periode.switch');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');
    
    // Students Management
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::delete('/students/destroy-all', [StudentController::class, 'destroyAll'])->name('students.destroyAll');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::delete('/students-delete-all', [StudentController::class, 'destroyAll'])->name('students.destroy-all');
    Route::post('/students/{student}/update-status', [StudentController::class, 'updateStatus'])->name('students.update-status');
    
    // Import/Export
    Route::get('/import', [ImportExportController::class, 'import'])->name('import');
    Route::post('/import', [ImportExportController::class, 'processImport'])->name('import.process');
    Route::get('/export', [ImportExportController::class, 'export'])->name('export');
    Route::get('/download-template', [ImportExportController::class, 'downloadTemplate'])->name('download.template');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});