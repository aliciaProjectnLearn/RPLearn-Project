<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\ModuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('student')->name('student. ')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index'])->name('module.index');
    Route::get('/modules/{id}', [ModuleController::class, 'show'])->name('modules.show');
});

Route::get('/student/modules/{id}/json', function($id) {
    // Kita tambahkan gradeCategory, subjectCategory, dan teacher agar datanya ada
    return \App\Models\Module::with(['contents', 'gradeCategory', 'subjectCategory', 'teacher'])->findOrFail($id);
});

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'guru') {
            return redirect()->route('dashboard.teacher');
        }
        return redirect()->route('dashboard.student');
    }

    return redirect()->route('login');
});

// Route::get('dashboard-student', function () {
//     return view('student.dashboard');
// });

Route::middleware(['auth'])->group(function () {
    // Ubah dari function () ke ModuleController
    Route::get('/dashboard/student', [ModuleController::class, 'index'])->name('dashboard.student');

    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
});


require __DIR__ . '/auth.php';
