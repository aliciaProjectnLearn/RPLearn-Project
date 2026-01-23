<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/dashboard/student', function () {
        return view('dashboard.student');
    })->name('dashboard.student');

    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
});


require __DIR__ . '/auth.php';
