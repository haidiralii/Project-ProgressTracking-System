<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DirectorDashboardController;
use App\Http\Controllers\OperatorDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OperatorJobController;
use App\Http\Controllers\ReportController;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// Dashboard sesuai role
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'admin'    => app(AdminDashboardController::class)->index(),
        'director' => app(DirectorDashboardController::class)->index(),
        'operator' => app(OperatorDashboardController::class)->index(),
        default    => abort(403, 'Unauthorized'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Profil user (semua role)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin area (hanya admin)
Route::middleware(['auth', 'can:register-admin'])->group(function () {
    // CRUD Project untuk Admin
    Route::resource('projects', ProjectController::class)->except(['index', 'show']);

    // CRUD User
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Pendaftaran user baru oleh admin
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Semua user login (admin, director, operator)
Route::middleware(['auth'])->group(function () {
    // Projects (index + show untuk non-admin)
    Route::resource('projects', ProjectController::class)->only(['index', 'show']);

    // Route khusus Director untuk halaman Projects
    Route::get('/projects/director', [ProjectController::class, 'directorIndex'])
        ->name('projects.director');

    // Jobs
    Route::resource('jobs', JobController::class)->except(['edit', 'update']);
    Route::post('/jobs/{job}/complete', [JobController::class, 'complete'])->name('jobs.complete');

    // Form feedback (GET)
    Route::get('/jobs/{job}/feedback', [\App\Http\Controllers\JobFeedbackController::class, 'create'])
        ->name('jobs.feedback.create');

    // Simpan feedback (POST)
    Route::post('/jobs/{job}/feedback', [\App\Http\Controllers\JobFeedbackController::class, 'store'])
        ->name('jobs.feedback.store');

    // Activities
    Route::resource('activities', JobActivityController::class);

    // Toggle starred project
    Route::post('/projects/{project}/toggle-star', [ProjectController::class, 'toggleStar'])->name('projects.toggleStar');

    // Admin edit job
    Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');

    // Operator update job
    Route::get('/jobs/{job}/update-operator', [JobController::class, 'updateOperatorForm'])->name('jobs.update_operator');
    Route::post('/jobs/{job}/update-operator', [JobController::class, 'updateOperator'])->name('jobs.update_operator.submit');
});

// Laporan (admin & director bisa semua, operator hanya miliknya)
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Export PDF & Excel
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');

    // Laporan per project
    Route::get('/reports/project/{project}', [ReportController::class, 'projectReport'])
        ->name('reports.project');
});

// Auth bawaan Laravel Breeze
require __DIR__ . '/auth.php';
