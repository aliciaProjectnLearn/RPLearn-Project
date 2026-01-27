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
    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');

    // PROFILE
    Route::get('/dashboard/profile', [ProfileController::class, 'index'])
        ->name('profile');

    // LOGOUT
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

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
