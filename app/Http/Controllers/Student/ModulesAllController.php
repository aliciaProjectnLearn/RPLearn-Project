<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory;
use App\Models\SaveModule;
use App\Models\SubjectCategory;
use Illuminate\Support\Facades\Auth;

class ModulesAllController extends Controller
{
    public function index(Request $request)
{
    // 1. Ambil data pendukung
    $grades = GradeCategory::all(); 
    $subjects = SubjectCategory::all();

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
    $userId = auth()->id();

    $modules = $query->get()->map(function ($module) use ($userId) {
        $module->isSaved = $module->savedByUsers()
            ->where('user_id', $userId)
            ->exists();
        return $module;
    });

    if ($request->ajax() || $request->has('ajax')) {
        return view('partials._module_list', compact('modules'))->render();
    }

    return view('student.modules.viewAll', compact(
        'modules',          // ✅ ini bikin error hilang
        'grades',
        'subjects',
    ));
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

}
