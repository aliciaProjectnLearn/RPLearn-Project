<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory;
use App\Models\SubjectCategory;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
public function index(Request $request)
{
    // Ambil data kategori
    $grades = GradeCategory::all();
    $subjects = SubjectCategory::all();

    $faqs = Question::with('answer')
        ->where('status', 'answered')
        ->whereHas('answer')
        ->latest()
        ->get();

    $teachers = User::where('role', 'guru')->get();

    // LOGIKA DICTIONARY YANG TADI DI WEB.PHP PINDAH KE SINI:
    $dictionaries = \App\Models\Dictionary::orderBy('term', 'asc')->limit(6)->get();

    $query = Module::with(['gradeCategory', 'subjectCategory', 'contents', 'teacher']);

    // Logika Filter Module
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $modules = $query->get();

    if ($request->ajax() || $request->has('ajax')) {
        return view('partials._module_list', compact('modules'))->render();
    }

    return view('dashboard.student', compact('modules', 'grades', 'subjects', 'dictionaries', 'faqs', 'teachers'));
}

    public function show($id)
    {
        // Jika butuh return view khusus detail
        $module = Module::with('contents')->findOrFail($id);
        return view('student.modules.show', compact('module'));
    }

    public function getModuleJson($id)
    {
        // Mengambil modul beserta relasi konten (video/pdf) dan kategorinya
        $module = \App\Models\Module::with(['contents', 'gradeCategory', 'subjectCategory'])->findOrFail($id);

        return response()->json($module);
    }
}
