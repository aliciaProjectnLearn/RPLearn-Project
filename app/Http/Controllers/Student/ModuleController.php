<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory;
use App\Models\SubjectCategory;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data kategori untuk filter dropdown
        $grades = GradeCategory::all();
        $subjects = SubjectCategory::all();

        // 2. Logika pencarian dan filter
        $query = Module::with(['gradeCategory', 'subjectCategory']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('grade_id')) {
            $query->where('grade_category_id', $request->grade_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_category_id', $request->subject_id);
        }

        $modules = $query->get();

        // 3. Arahkan ke view dashboard.student (SESUAIKAN PATHNYA)
        // Karena di route tadi viewnya dashboard.student, maka:
        return view('dashboard.student', compact('modules', 'grades', 'subjects'));
    }

    public function show($id)
    {
        // Jika butuh return view khusus detail (tapi kita pakai JSON untuk modal dashboard)
        $module = Module::with('contents')->findOrFail($id);
        return view('student.modules.show', compact('module'));
    }
}
