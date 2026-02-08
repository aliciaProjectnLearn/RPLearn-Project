<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use App\Models\Dictionary;

class AdminController extends Controller
{
    public function index()
    {
        // Checklist Trello: Menampilkan ringkasan statistik
        $summary = [
            'total_modul' => Module::count(),
            'total_user'  => User::count(),
            'total_kamus' => Dictionary::count(),
        ];

        return view('admin.dashboard', compact('summary'));
    }
}
