<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\DictionaryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root Redirect Logic
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru'  => redirect()->route('dashboard.teacher'),
            default => redirect()->route('student.dashboard'),
        };
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Trello Task #15)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // 1. RUTE KHUSUS ISI MATERI (WAJIB ADA)
    Route::get('modules/{id}/add-content', [App\Http\Controllers\Admin\ModuleController::class, 'addContent'])
        ->name('modules.addContent');
    Route::post('modules/{id}/store-content', [App\Http\Controllers\Admin\ModuleController::class, 'storeContent'])
        ->name('modules.storeContent');

    // 2. RESOURCE UTAMA
    Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('dictionaries', App\Http\Controllers\Admin\DictionaryController::class);
    Route::resource('users', UserController::class);

    // 3. FITUR FAQ
    Route::get('faq', [App\Http\Controllers\Admin\FAQController::class, 'index'])->name('faq.index');
    Route::post('faq/{id}/answer', [App\Http\Controllers\Admin\FAQController::class, 'answer'])->name('faq.answer');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentModuleController::class, 'index'])->name('dashboard');
    Route::get('/modules/{id}', [StudentModuleController::class, 'show'])->name('modules.show');

    // API/JSON route dipindah ke dalam grup agar aman (terproteksi auth)
    Route::get('/modules/{id}/json', function($id) {
        return \App\Models\Module::with(['contents', 'gradeCategory', 'subjectCategory', 'teacher'])->findOrFail($id);
    });
});

/*
|--------------------------------------------------------------------------
| Shared Routes (Teacher & Profile)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
