<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use App\Models\Dictionary;
use App\Models\Student;
use App\Models\Teacher;

class AdminController extends Controller
{
    public function index()
    {
        // Checklist Trello: Menampilkan ringkasan statistik
        $summary = [
            'total_modul' => Module::count(),
            'total_user'  => User::count(),
            'total_kamus' => Dictionary::count(),

        // Statistik role
        'total_siswa'   => Student::count(),
        'total_guru'    => Teacher::count(),
        'total_admin'   => User::where('role','admin')->count(),

        // User terbaru join
        'user_terbaru'  => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('summary'));
    }
}
