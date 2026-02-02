<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\Student\ModuleController;
use App\Models\Dictionary;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'guru'
            ? redirect()->route('dashboard.teacher')
            : redirect()->route('dashboard.student');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // DASHBOARD SISWA
    Route::get('/dashboard/student', function () {
        $dictionaries = Dictionary::orderBy('term')->limit(6)->get();
        $grades = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $faqs = \App\Models\Question::latest()->limit(5)->get();
        $modules = \App\Models\Module::with(['gradeCategory', 'subjectCategory'])->get();
        $teachers = \App\Models\User::where('role', 'guru')->limit(6)->get();

        return view('dashboard.student', compact(
            'dictionaries',
            'grades',
            'subjects',
            'modules',
            'faqs',
            'teachers'
        ));
    })->name('dashboard.student');

    // DASHBOARD GURU
    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');

    // PROFILE
    Route::get('/dashboard/profile', [ProfileController::class, 'index'])
        ->name('profile');
});

/*
|--------------------------------------------------------------------------
| STUDENT MODULE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:siswa'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/modules', [ModuleController::class, 'index'])
            ->name('modules.index');

        Route::get('/modules/{id}', [ModuleController::class, 'show'])
            ->name('modules.show');

        Route::get('/modules/{id}/json', function ($id) {
            return \App\Models\Module::with([
                'contents',
                'gradeCategory',
                'subjectCategory',
                'teacher'
            ])->findOrFail($id);
        })->name('modules.json');
    });

/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
*/
Route::post('/faq', [FAQController::class, 'store'])->middleware('auth');
Route::get('/faq/search', [FAQController::class, 'index']);

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
