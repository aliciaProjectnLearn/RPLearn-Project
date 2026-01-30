<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // DASHBOARD SISWA (FAQ + SEARCH + FORM TANYA)
    Route::get('/dashboard/student', [FAQController::class, 'student'])
        ->name('dashboard.student');

    // DASHBOARD GURU
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\ModuleController;
use Illuminate\Support\Facades\Route;
use App\Models\Dictionary;

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

Route::middleware(['auth'])->group(function () {
    // Ubah dari function () ke ModuleController
    Route::get('/dashboard/student', [ModuleController::class, 'index'])->name('dashboard.student');

    // Bisa di aktifkan jika perlu(tidak jadi dihapus).
    // Route::get('/dashboard/student', function () {
    //     $dictionaries = Dictionary::orderBy('term', 'asc')
    //         ->limit(6)
    //         ->get();
    //     $grades=\App\Models\GradeCategory::all();
    //     $subjects=\App\Models\SubjectCategory::all();
    //     $modules = \App\Models\Module::with(['gradeCategory', 'subjectCategory'])->get();
    //     return view('dashboard.student', compact('dictionaries', 'grades', 'subjects', 'modules'));
    // })->name('dashboard.student');


    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');

    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
});

        return redirect()->route('login');
    })->name('logout');

    // FAQ
    Route::post('/faq', [FAQController::class, 'store']);
    Route::get('/faq/search', [FAQController::class, 'index']); 
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
