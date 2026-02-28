<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory;
use App\Models\SaveModule;
use App\Models\SubjectCategory;
use App\Models\ModuleView;

class ModulesAllController extends Controller
{
    public function index(Request $request)
    {
        $grades   = GradeCategory::all();
        $subjects = SubjectCategory::all();

        // ✅ Ambil kelas_id siswa yang login
        $student  = \App\Models\Student::where('user_id', auth()->id())->first();
        $kelasId  = $student?->kelas_id;

        $query = Module::with(['gradeCategory', 'subjectCategory', 'contents', 'teacher', 'approval', 'kelas'])
            ->whereHas('approval', function ($q) {
                $q->where('status', 'approved');
            });

        // ✅ Filter hanya modul dari kelas siswa
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_category_id', $request->subject_id);
        }

        $userId = auth()->id();

        // ✅ Ambil ID modul yang sudah pernah dibuka siswa ini
        $viewedModuleIds = $student
            ? ModuleView::where('student_id', $student->id)->pluck('module_id')->toArray()
            : [];

        $modules = $query->get()->map(function ($module) use ($userId, $viewedModuleIds) {
            $module->isSaved  = $module->savedByUsers()->where('user_id', $userId)->exists();
            $module->isViewed = in_array($module->id, $viewedModuleIds);
            return $module;
        });

        if ($request->ajax() || $request->has('ajax')) {
            return view('partials._module_list', compact('modules'))->render();
        }

        return view('student.modules.viewAll', compact('modules', 'grades', 'subjects'));
    }
}
