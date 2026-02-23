<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // SUMMARY DATA
        $summary = [
            'total_modul' => Module::where('teacher_id', $user->id)->count(),

            'total_pertanyaan' => Question::where('teacher_id', $user->id)->count(),

            'total_jawaban' => Answer::where('teacher_id', $user->id)->count(),

            'total_siswa' => User::where('role', 'siswa')->count(),

            'modul_terbaru' => Module::where('teacher_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
        ];

        // GRAFIK MODUL PER BULAN
        $rawModules = Module::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('teacher_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $months = [];
        $totals = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            $totals[] = $rawModules->firstWhere('month', $i)->total ?? 0;
        }

        return view('teacher.dashboard', compact('summary', 'months', 'totals'));
    }
}
