<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

// ==================== AUTHENTICATION ROUTES (FIRST PRIORITY) ====================
Route::controller(AuthController::class)->group(function () {
    // Redirect root to login as first priority
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register routes
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Logout routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
});

// ==================== PUBLIC ROUTES (NO AUTH REQUIRED) ====================

// Beranda route - public access, no middleware
Route::get('/beranda', function () {
    return view('home', [
        'title' => 'Beranda',
        'active' => 'beranda',
    ]);
})->name('beranda');

// Home route - public access, no middleware
Route::get('/home', function () {
    return view('home', [
        'title' => 'Home',
        'active' => 'home',
    ]);
})->name('home');

// ==================== PROGRAM UNGGULAN & KURIKULUM ROUTES ====================
// Route untuk halaman program unggulan dan kurikulum - PUBLIC
Route::get('/program-unggulan', [ProgramController::class, 'index'])->name('program.unggulan');

// Route alternatif untuk program unggulan
Route::get('/program', function () {
    return redirect()->route('program.unggulan');
})->name('program');

Route::get('/kurikulum', function () {
    return redirect()->route('program.unggulan');
})->name('kurikulum');

// Route untuk detail program (jika ingin membuat halaman detail per program)
Route::get('/program/{slug}', [ProgramController::class, 'show'])->name('program.show');

// Route untuk API program (jika diperlukan untuk AJAX)
Route::get('/api/program-stats', [ProgramController::class, 'getStats'])->name('api.program.stats');

// ==================== SISWA ROUTES - PUBLIC ACCESS ====================
Route::get('/siswa', [PostController::class, 'index'])->name('siswa.index');
Route::get('/siswa/create', [PostController::class, 'create'])->name('siswa.create');
Route::post('/siswa', [PostController::class, 'store'])->name('siswa.store');
Route::get('/siswa/{id}', [PostController::class, 'show'])->name('siswa.show');
Route::get('/siswa/{id}/edit', [PostController::class, 'edit'])->name('siswa.edit');
Route::put('/siswa/{id}', [PostController::class, 'update'])->name('siswa.update');
Route::get('/siswa/{id}/delete', [PostController::class, 'delete'])->name('siswa.delete');
Route::delete('/siswa/{id}', [PostController::class, 'destroy'])->name('siswa.destroy');

// Public pendaftaran routes (alias untuk siswa.create)
Route::get('/pendaftaran', [PostController::class, 'create'])->name('pendaftaran');
Route::post('/pendaftaran', [PostController::class, 'store'])->name('pendaftaran.store');

// Public registration aliases
Route::get('/daftar', [PostController::class, 'create'])->name('daftar');
Route::get('/daftar-siswa', [PostController::class, 'create'])->name('daftar-siswa');
Route::get('/registrasi', [PostController::class, 'create'])->name('registrasi');

// Statistics routes - public
Route::get('/statistik', [PostController::class, 'statistik'])->name('statistik');
Route::get('/statistik-nilai', [PostController::class, 'statistikNilai'])->name('statistik.nilai');

// Public photo routes
Route::get('/siswa/{id}/photo', [PostController::class, 'photo'])->name('siswa.photo');
Route::get('/siswa/{id}/download-photo', [PostController::class, 'downloadPhoto'])->name('siswa.download-photo');

// Public dashboard and validation
Route::get('/dashboard', [PostController::class, 'dashboard'])->name('siswa.dashboard');
Route::get('/siswa/{id}/validate', [PostController::class, 'validateData'])->name('siswa.validate');

// Public list and search
Route::get('/siswa-list', [PostController::class, 'list'])->name('siswa.list');
Route::get('/siswa/status/{status}', [PostController::class, 'byStatus'])->name('siswa.by-status');
Route::get('/cari-siswa', function() {
    return redirect()->route('siswa.index');
})->name('cari-siswa');

// Public export (limited data for non-admin)
Route::get('/siswa/export', [PostController::class, 'export'])->name('siswa.export');

// ==================== INFORMASI SEKOLAH ROUTES ====================
// Route untuk halaman tentang sekolah
Route::get('/tentang', function () {
    return view('tentang', [
        'title' => 'Tentang Sekolah',
        'active' => 'tentang',
    ]);
})->name('tentang');

// Route untuk kontak
Route::get('/kontak', function () {
    return view('kontak', [
        'title' => 'Kontak',
        'active' => 'kontak',
    ]);
})->name('kontak');

// Route untuk fasilitas
Route::get('/fasilitas', function () {
    return view('fasilitas', [
        'title' => 'Fasilitas',
        'active' => 'fasilitas',
    ]);
})->name('fasilitas');

// Route untuk prestasi
Route::get('/prestasi', function () {
    return view('prestasi', [
        'title' => 'Prestasi',
        'active' => 'prestasi',
    ]);
})->name('prestasi');

// ==================== API ROUTES FOR AJAX - PUBLIC ====================
// Route API untuk mendapatkan data program via AJAX
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/programs', [ProgramController::class, 'apiIndex'])->name('programs');
    Route::get('/kurikulum', [ProgramController::class, 'apiKurikulum'])->name('kurikulum');
    Route::get('/school-stats', [ProgramController::class, 'apiSchoolStats'])->name('school-stats');
});

// ==================== ADMIN-ONLY ROUTES (REQUIRES AUTHENTICATION) ====================
Route::middleware('auth')->group(function () {
    
    // Admin-only student management
    Route::patch('/siswa/{id}/update-status', [PostController::class, 'updateStatus'])->name('siswa.updateStatus');
    Route::post('/siswa/bulk-update-status', [PostController::class, 'bulkUpdateStatus'])->name('siswa.bulk-update-status');
    Route::patch('/siswa/{id}/reset-password', [PostController::class, 'resetPassword'])->name('siswa.reset-password');

    // Admin specific routes
    Route::get('/admin/export', [PostController::class, 'export'])->name('admin.siswa.export');
    Route::get('/admin/dashboard', [PostController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin home redirect
    Route::get('/admin/home', function () {
        return redirect()->route('admin.dashboard');
    })->name('admin.home');

    // ==================== ADMIN PROGRAM MANAGEMENT ====================
    // Route untuk admin mengelola program unggulan
    Route::prefix('admin/program')->name('admin.program.')->group(function () {
        Route::get('/', [ProgramController::class, 'index'])->name('index');
        Route::get('/create', [ProgramController::class, 'create'])->name('create');
        Route::post('/', [ProgramController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProgramController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProgramController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProgramController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [ProgramController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Route untuk admin mengelola kurikulum
    Route::prefix('admin/kurikulum')->name('admin.kurikulum.')->group(function () {
        Route::get('/', [ProgramController::class, 'kurikulumIndex'])->name('index');
        Route::get('/create', [ProgramController::class, 'kurikulumCreate'])->name('create');
        Route::post('/', [ProgramController::class, 'kurikulumStore'])->name('store');
        Route::get('/{id}/edit', [ProgramController::class, 'kurikulumEdit'])->name('edit');
        Route::put('/{id}', [ProgramController::class, 'kurikulumUpdate'])->name('update');
        Route::delete('/{id}', [ProgramController::class, 'kurikulumDestroy'])->name('destroy');
    });
});

// ==================== FALLBACK ROUTES ====================
// Route fallback untuk halaman yang tidak ditemukan
Route::fallback(function () {
    return view('errors.404', [
        'title' => 'Halaman Tidak Ditemukan',
        'active' => '',
    ]);
});