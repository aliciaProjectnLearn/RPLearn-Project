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

    // FAQ
    $faqs = Question::with('answer')
        ->where('status', 'answered')
        ->whereHas('answer')
        ->latest()
        ->get();

    // Guru
    $teachers = User::where('role', 'guru')->get();

    // LOGIKA DICTIONARY YANG TADI DI WEB.PHP PINDAH KE SINI:
    $dictionaries = \App\Models\Dictionary::orderBy('term', 'asc')->limit(6)->get();

    // Query Modul
    $query = Module::with(['gradeCategory', 'subjectCategory', 'contents', 'teacher']);

    // Logika Filter Module
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Filter Kelas
    if ($request->filled('grade_id')) {
        $query->where('grade_category_id', $request->grade_id);
    }

    // Filter Materi
    if ($request->filled('subject_id')) {
        $query->where('subject_category_id', $request->subject_id);
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

   public function toggleLike(Request $request, $id)
{
    $module = Module::findOrFail($id);

    // liked = true  -> tambah like
    // liked = false -> kurang like
    if ($request->liked === true) {
        $module->like += 1;
    } else {
        $module->like = max(0, $module->like - 1);
    }

    $module->save();

    return response()->json([
        'success' => true,
        'like' => $module->like
    ]);
}

}
