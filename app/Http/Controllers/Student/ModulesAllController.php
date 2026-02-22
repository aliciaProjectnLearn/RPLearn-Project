<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\GradeCategory as Grade;
use App\Models\SubjectCategory;

class ModulesAllController extends Controller
{
     public function index(Request $request)
{
    $grades = Grade::all();
    $subjects = SubjectCategory::all();

    $query = Module::with(['teacher','gradeCategory','subjectCategory','contents']);

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('grade_id')) {
        $query->where('grade_category_id', $request->grade_id);
    }

    if ($request->filled('subject_id')) {
        $query->where('subject_category_id', $request->subject_id);
    }

    $modules = $query->latest()->get();

    if ($request->ajax() || $request->has('ajax')) {
        return view('partials._module_list', compact('modules'))->render();
    }

    return view('student.modules.viewAll', compact('modules','grades','subjects'));
}

}
