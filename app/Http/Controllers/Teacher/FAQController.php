<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{
    public function index()
    {
        // Ambil semua pertanyaan, prioritaskan yang 'pending'
        $questions = Question::with(['student', 'answer'])->latest()->get();
        return view('teacher.faq.index', compact('questions'));
    }

    public function answer(Request $request, $id)
    {
        $request->validate(['answer' => 'required|string']);

        // 1. Simpan Jawaban
        Answer::create([
            'question_id' => $id,
            'teacher_id' => Auth::id(), // Admin yang sedang login
            'answer' => $request->answer,
        ]);

        // 2. Update Status Pertanyaan
        Question::where('id', $id)->update(['status' => 'answered']);

        return back()->with('success', 'Jawaban berhasil dikirim ke siswa!');
    }
}

// namespace App\Http\Controllers\Teacher;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Question;
// use App\Models\User;

// class FAQController extends Controller
// {
//     /**
//      * =========================
//      * VIEW FAQ DI DASHBOARD TEACHER
//      * =========================
//      */
//     public function student(Request $request)
//     {
//         $faqs = Question::with('answer')
//             ->where('status', 'answered')
//             ->whereHas('answer')
//             ->when($request->search, function ($query) use ($request) {
//                 $query->where('question', 'like', '%' . $request->search . '%');
//             })
//             ->latest()
//             ->get();

//         $teachers = User::where('role', 'guru')->get();

//         return view('dashboard.student', compact('faqs', 'teachers'));
//     }

//     /**
//      * =========================
//      * API: LIST FAQ (SEARCH)
//      * =========================
//      */
//     public function index(Request $request)
//     {
//         $faqs = Question::with('answer')
//             ->where('status', 'answered')
//             ->whereHas('answer')
//             ->when($request->search, function ($query) use ($request) {
//                 $query->where('question', 'like', '%' . $request->search . '%');
//             })
//             ->when($request->module, function ($query) use ($request) {
//                 $query->where('module_id', $request->module);
//             })
//             ->latest()
//             ->get();

//         return response()->json($faqs);
//     }

//     /**
//      * =========================
//      * API: DETAIL FAQ
//      * =========================
//      */
//     public function show($id)
//     {
//         $faq = Question::with('answer')
//             ->where('status', 'answered')
//             ->whereHas('answer')
//             ->find($id);

//         if (!$faq) {
//             return response()->json([
//                 'message' => 'FAQ tidak ditemukan'
//             ], 404);
//         }

//         return response()->json($faq);
//     }

//     /**
//      * =========================
//      * FORM TANYA (SIMPAN PERTANYAAN)
//      * =========================
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'question'   => 'required|string',
//             'teacher_id' => 'required|exists:users,id',
//             'module_id'  => 'nullable|exists:modules,id',
//         ]);

//         $question = Question::create([
//             'student_id' => auth()->user()->student->id,
//             'teacher_id' => $request->teacher_id,
//             'module_id'  => $request->module_id,
//             'title'      => $request->title,
//             'question'   => $request->question,
//             'status'     => 'pending',
//         ]);

//         return response()->json([
//             'message' => 'Pertanyaan berhasil dikirim',
//             'data'    => $question
//         ], 201);
//     }
// }
