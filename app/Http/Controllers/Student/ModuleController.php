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
use App\Models\SaveModule;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
public function index(Request $request)
{
    $grades       = GradeCategory::all();
    $subjects     = SubjectCategory::all();
    $teachers     = User::where('role', 'guru')->get();
    $dictionaries = Dictionary::orderBy('term', 'asc')->limit(6)->get();

    $faqs = Question::with('answer')
        ->where('status', 'answered')
        ->whereHas('answer')
        ->latest()
        ->limit(5)
        ->get();

    // ✅ ambil siswa login
    $student = \App\Models\Student::where('user_id', auth()->id())->first();
    $kelasId = $student?->kelas_id;

    // ✅ query dasar
    $query = Module::with(['gradeCategory', 'subjectCategory', 'teacher', 'approval'])
        ->whereHas('approval', function ($q) {
            $q->where('status', 'approved');
        })
        ->when($kelasId, fn($q) => $q->whereHas('kelasList', fn($k) => $k->where('kelas.id', $kelasId)));

    // ✅ FILTER SEARCH
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // ✅ FILTER GRADE
    if ($request->filled('grade_id')) {
        $query->where('grade_category_id', $request->grade_id);
    }

    // ✅ FILTER SUBJECT
    if ($request->filled('subject_id')) {
        $query->where('subject_category_id', $request->subject_id);
    }

    $modules = $query->get();

    $userId = auth()->id();

    // ✅ ambil semua module yang sudah pernah dibuka SEKALI saja
    $viewedModuleIds = $student
        ? \App\Models\ModuleView::where('student_id', $student->id)
            ->pluck('module_id')
            ->toArray()
        : [];

    // ✅ inject attribute tambahan
    $modules->map(function ($module) use ($userId, $viewedModuleIds) {
        $module->isSaved  = $module->savedByUsers()
            ->where('user_id', $userId)
            ->exists();

        $module->isViewed = in_array($module->id, $viewedModuleIds);

        return $module;
    });

    // ✅ Top module tetap
    $topModules = Module::with(['gradeCategory', 'subjectCategory', 'teacher', 'approval'])
        ->whereHas('approval', function ($q) {
            $q->where('status', 'approved');
        })
        ->when($kelasId, fn($q) => $q->whereHas('kelasList', fn($k) => $k->where('kelas.id', $kelasId)))
        ->orderByDesc('like')
        ->limit(3)
        ->get();

    if ($request->ajax()) {
        return view('partials._module_list', ['modules' => $modules])->render();
    }

    return view('dashboard.student', compact(
        'topModules',
        'modules',
        'grades',
        'subjects',
        'dictionaries',
        'faqs',
        'teachers'
    ));
}

    public function show($id)
    {
        $module = Module::with(['contents', 'kelas', 'subjectCategory', 'gradeCategory', 'teacher'])->findOrFail($id);

        // ✅ Auth check: pastikan siswa hanya bisa akses modul kelasnya
        $student = \App\Models\Student::where('user_id', auth()->id())->first();

        if ($student && $module->kelasList->count() > 0) {
            $allowed = $module->kelasList->pluck('id')->contains($student->kelas_id);
            if (!$allowed) {
                abort(403, 'Kamu tidak punya akses ke modul ini.');
            }
        }

        // ✅ Tracking view — cegah duplikasi
        if ($student) {
            \App\Models\ModuleView::firstOrCreate([
                'module_id'  => $module->id,
                'student_id' => $student->id,
            ], [
                'viewed_at' => now(),
            ]);
        }

        return view('student.modules.show', compact('module'));
    }

    public function getModuleJson($id)
    {
        $module = Module::with(['contents', 'gradeCategory', 'subjectCategory'])
            ->whereHas('approval', function ($query) {
                $query->where('status', 'approved');
            })
            ->findOrFail($id);

        $module->load('likes');

        return response()->json([
            'id'               => $module->id,
            'title'            => $module->title,
            'desc'             => $module->desc,
            'contents'         => $module->contents,
            'grade_category'   => $module->gradeCategory,
            'subject_category' => $module->subjectCategory,
            'isLiked'          => auth()->check() && $module->likes()->where('user_id', auth()->id())->exists()
        ]);
    }

    public function saves()
    {
        return $this->hasMany(SaveModule::class);
    }

    public function getIsSavedAttribute()
    {
        return auth()->check() &&
            $this->saves()->where('user_id', auth()->id())->exists();
    }

    public function toggleLike($id)
    {
        $module = Module::findOrFail($id);
        $user   = auth()->user();

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

    public function downloadPdf($contentId)
    {
        $content = \App\Models\ModuleContent::findOrFail($contentId);

        if (!$content->file_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($content->file_path)) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        $student = \App\Models\Student::where('user_id', auth()->id())->first();
        $module  = \App\Models\Module::findOrFail($content->module_id);

        if ($student && $module->kelasList->count() > 0) {
            $allowed = $module->kelasList->pluck('id')->contains($student->kelas_id);
            if (!$allowed) {
                abort(403, 'Kamu tidak punya akses ke file ini.');
            }
        }

        $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($content->file_path);
        $fileName = $content->title . '.pdf';

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
