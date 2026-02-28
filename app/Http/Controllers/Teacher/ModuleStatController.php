<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Student;
use App\Models\ModuleView;

class ModuleStatController extends Controller
{
    public function show($id)
    {
        $module = Module::findOrFail($id);

        // ✅ Authorization: guru hanya bisa lihat modul miliknya
        if ($module->teacher_id !== auth()->id()) {
            abort(403, 'Kamu tidak punya akses ke statistik modul ini.');
        }

        // Siswa yang sudah membuka modul (dari kelas yang diampu guru)
        $teacher     = \App\Models\Teacher::where('user_id', auth()->id())->first();
        $kelasIds    = $teacher ? $teacher->kelas->pluck('id')->toArray() : [];

        // Semua siswa dari kelas yang diampu guru
        $allStudents = Student::whereIn('kelas_id', $kelasIds)->get();

        // Siswa yang sudah view
        $viewedIds   = ModuleView::where('module_id', $id)
                        ->pluck('student_id')
                        ->toArray();

        $studentsViewed  = $allStudents->whereIn('id', $viewedIds);
        $studentsNotYet  = $allStudents->whereNotIn('id', $viewedIds);

        return view('teacher.modules.statistik', compact(
            'module',
            'allStudents',
            'studentsViewed',
            'studentsNotYet'
        ));
    }
}
