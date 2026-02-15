<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory;
use App\Models\SubjectCategory;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

       $module->load('likes');

$isLiked = auth()->check() && 
    $module->likes->contains('user_id', auth()->id());

return response()->json([
    'id' => $module->id,
    'title' => $module->title,
    'desc' => $module->desc,
    'contents' => $module->contents,
    'grade_category' => $module->gradeCategory,
    'subject_category' => $module->subjectCategory,
    'isLiked' => $module->likes()
        ->where('user_id', auth()->id())
        ->exists()
]);
    }

public function toggleLike($id)
{
    $module = Module::findOrFail($id);
    $user = auth()->user();

    $like = $module->likes()->where('user_id', $user->id)->first();

    if ($like) {
        $like->delete();

        // ⬇️ TARUH DI SINI
        $module->decrement('like');

        return response()->json(['liked' => false]);
    } else {
        $module->likes()->create([
            'user_id' => $user->id
        ]);

        // ⬇️ TARUH DI SINI
        $module->increment('like');

        return response()->json(['liked' => true]);
    }
}

public function save($id)
{
    $modul = Module::findOrFail($id);

    auth()->user()->savedModuls()->syncWithoutDetaching([$modul->id]);

    return response()->json([
        'status' => 'saved'
    ]);
}

public function unsave($id)
{
    $modul = Module::findOrFail($id);

    auth()->user()->savedModuls()->detach($modul->id);

    return response()->json([
        'status' => 'unsaved'
    ]);
}


}
