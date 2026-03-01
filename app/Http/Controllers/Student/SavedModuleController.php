<?php

namespace App\Http\Controllers\Student;

use App\Models\Module;
use App\Models\SaveModule;
use App\Models\Student;
use App\Models\ModuleView;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SavedModuleController extends Controller
{
    // =========================
    // TOGGLE SAVE
    // =========================
    public function toggleSave(Module $module)
    {
        $user = Auth::user();

        $saved = SaveModule::where('user_id', $user->id)
                    ->where('module_id', $module->id)
                    ->first();

        if ($saved) {
            $saved->delete();
            return response()->json(['saved' => false]);
        }

        SaveModule::firstOrCreate([
            'user_id'  => $user->id,
            'module_id'=> $module->id
        ]);

        return response()->json(['saved' => true]);
    }


    // =========================
    // HALAMAN MODUL TERSIMPAN
    // =========================
   // =========================
    // HALAMAN MODUL TERSIMPAN
    // =========================
    public function index(\Illuminate\Http\Request $request) // <-- Tambahkan Request $request
    {
        $userId = auth()->id();
        $search = $request->query('search'); // Ambil input search dari JS

        // 🔥 ambil student
        $student = Student::where('user_id', $userId)->first();

        // 🔥 ambil modul yang pernah dibuka
        $viewedIds = $student
            ? ModuleView::where('student_id', $student->id)
                ->pluck('module_id')
                ->toArray()
            : [];

        // 🔥 ambil modul tersimpan dengan filter SEARCH
        $modules = Module::whereHas('savedByUsers', function($q) use ($userId){
                $q->where('user_id', $userId);
            })
            // Tambahkan logika pencarian berdasarkan judul modul
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->with(['teacher','gradeCategory','subjectCategory','contents'])
            ->latest()
            ->get()
            ->map(function ($module) use ($viewedIds) {
                $module->isSaved  = true;
                $module->isViewed = in_array($module->id, $viewedIds);
                return $module;
            });

        // 🔥 LOGIKA PENENTU RESPON (PENTING!)
        if ($request->ajax()) {
            // Jika request AJAX, kirim HANYA potongan kartu modulnya saja
            return view('partials._module_saved', compact('modules'))->render();
        }

        // Jika akses biasa melalui browser, kirim halaman utuh
        return view('student.modules.saved', compact('modules'));
    }
}