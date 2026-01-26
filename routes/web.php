<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Dictionary;

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
        $dictionaries = Dictionary::orderBy('term', 'asc')
            ->limit(6)
            ->get();

        return view('dashboard.student', compact('dictionaries'));
    })->name('dashboard.student');


    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher');
    })->name('dashboard.teacher');

    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
});



require __DIR__ . '/auth.php';
