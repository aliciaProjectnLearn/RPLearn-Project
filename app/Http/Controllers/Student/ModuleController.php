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
    // Ambil data kategori
    $grades = GradeCategory::all();
    $subjects = SubjectCategory::all();

    // LOGIKA DICTIONARY YANG TADI DI WEB.PHP PINDAH KE SINI:
    $dictionaries = \App\Models\Dictionary::orderBy('term', 'asc')->limit(6)->get();

    $query = Module::with(['gradeCategory', 'subjectCategory', 'contents', 'teacher']);

    // Logika Filter Module
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    // ... (filter grade & subject tetap di sini) ...

    $modules = $query->get();

    // GERBANG AJAX:
    if ($request->ajax() || $request->has('ajax')) {
        return view('partials._module_list', compact('modules'))->render();
    }

    // Kirim semua variabel ke view (tambah dictionaries!)
    return view('dashboard.student', compact('modules', 'grades', 'subjects', 'dictionaries'));
}

    public function show($id)
    {
        // Jika butuh return view khusus detail
        $module = Module::with('contents')->findOrFail($id);
        return view('student.modules.show', compact('module'));
    }
}
