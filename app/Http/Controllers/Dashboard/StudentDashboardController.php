<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dictionary;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $dictionaries = Dictionary::orderBy('term', 'asc')
            ->limit(6)
            ->get();

        return view('dashboard.student', compact('dictionaries'));
    }
}
