<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory;
use App\Models\SubjectCategory;
use App\Models\Question;
use App\Models\User;
use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
public function index(Request $request)
{
    // 1. Ambil data pendukung
    $grades = GradeCategory::all();
    $subjects = SubjectCategory::all();
    $teachers = User::where('role', 'guru')->get();
    $dictionaries = Dictionary::orderBy('term', 'asc')->limit(6)->get();

    $faqs = Question::with('answer')
        ->where('status', 'answered')
        ->whereHas('answer')
        ->latest()
        ->get();

    // 2. Top modules (tetap)
    $topModules = Module::with(['gradeCategory', 'subjectCategory', 'teacher', 'approval'])
        ->whereHas('approval', function ($query) {
            $query->where('status', 'approved');
        })
        ->orderByDesc('like')
        ->limit(3)
        ->get();

    // 3. QUERY MODUL UTAMA → HANYA 3 TERBARU
    $query = Module::with(['gradeCategory', 'subjectCategory', 'contents', 'teacher', 'approval'])
        ->whereHas('approval', function ($query) {
            $query->where('status', 'approved');
        });

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('grade_id')) {
        $query->where('grade_category_id', $request->grade_id);
    }
    if ($request->filled('subject_id')) {
        $query->where('subject_category_id', $request->subject_id);
    }

    // 👉 INI YANG DIPERBAIKI
    $modules = $query->latest()->limit(3)->get();

    // kalau masih butuh grouping
    $groupedModules = $modules->groupBy(function($module) {
        return $module->gradeCategory
            ? $module->gradeCategory->grade
            : 'Tidak Ada Kelas';
    });

    if ($request->ajax() || $request->has('ajax')) {
        return view('partials._module_list', compact('groupedModules'))->render();
    }

    return view('dashboard.student', compact(
        'topModules',
        'modules',          // ✅ ini bikin error hilang
        'groupedModules',
        'grades',
        'subjects',
        'dictionaries',
        'faqs',
        'teachers'
    ));
}

    public function show($id)
    {
        $module = Module::with('contents')->findOrFail($id);
        return view('student.modules.show', compact('module'));
    }

    // TUGAS CARD 14: Mengambil detail modul berdasarkan ID
    public function getModuleJson($id)
    {
        // Pastikan API detail juga hanya bisa diakses kalau modulnya sudah approved
        $module = Module::with(['contents', 'gradeCategory', 'subjectCategory'])
            ->whereHas('approval', function ($query) {
                $query->where('status', 'approved');
            })
            ->findOrFail($id); // findOrFail otomatis memberikan error 404 (Data tidak ditemukan)

        $module->load('likes');

        return response()->json([
            'id' => $module->id,
            'title' => $module->title,
            'desc' => $module->desc,
            'contents' => $module->contents,
            'grade_category' => $module->gradeCategory,
            'subject_category' => $module->subjectCategory,
            'isLiked' => auth()->check() && $module->likes()->where('user_id', auth()->id())->exists()
        ]);
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


        public function toggleLike($id)
        {
            $module = Module::findOrFail($id);
            $user = auth()->user();

            $like = $module->likes()->where('user_id', $user->id)->first();

            if ($like) {
                $like->delete();
                $module->decrement('like');
                return response()->json(['liked' => false]);
            } else {
                $module->likes()->create(['user_id' => $user->id]);
                $module->increment('like');
                return response()->json(['liked' => true]);
            }
        }
}
