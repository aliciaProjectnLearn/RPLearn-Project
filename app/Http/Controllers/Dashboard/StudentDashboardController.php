<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dictionary;
use App\Models\Module;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Ambil kamus (6 term)
        $dictionaries = Dictionary::orderBy('term', 'asc')
            ->limit(5)
            ->get();

        // Ambil modul + relasi teacher
        $modules = Module::with(['teacher', 'gradeCategory', 'subjectCategory'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.student', compact('dictionaries', 'modules'));
    }
}
