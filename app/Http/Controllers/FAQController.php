<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\User;

class FAQController extends Controller
{
    public function all()
    {
        $faqs = Question::with('answer')
            ->where('status', 'answered')
            ->whereHas('answer')
            ->latest()
            ->paginate(10);

        return view('student.faq.allQuestion', compact('faqs'));
    }

    /**
     * =========================
     * API: LIST FAQ (SEARCH)
     * =========================
     */

    public function index(Request $request)
    {
        $faqs = Question::with([
                'answer:id,question_id,answer'
            ])
            ->where('status', 'answered')
            ->whereHas('answer')
            ->when($request->search, function ($query) use ($request) {
                $query->where('question', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->limit(20)
            ->get(['id','title','question']);

        return response()->json($faqs);
    }

    /**
     * =========================
     * API: DETAIL FAQ
     * =========================
     */
    public function show($id)
    {
        $faq = Question::with('answer')
            ->where('status', 'answered')
            ->whereHas('answer')
            ->find($id);

        if (!$faq) {
            return response()->json([
                'message' => 'FAQ tidak ditemukan'
            ], 404);
        }

        return response()->json($faq);
    }

    /**
     * =========================
     * FORM TANYA (SIMPAN PERTANYAAN)
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'question'   => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'title'      => 'required|string|max:255',
        ]);

        // 🔥 Ambil student berdasarkan user yang login (SESUIAI struktur tabel kamu)
        $student = \App\Models\Student::where('user_id', auth()->id())->first();

        // Kalau tidak ketemu, ini penyebab error popup kamu
        if (!$student) {
            return response()->json([
                'message' => 'Data siswa tidak ditemukan untuk akun ini.'
            ], 422);
        }

        $question = Question::create([
            'student_id' => $student->id,
            'teacher_id' => $request->teacher_id,
            'title'      => $request->title,
            'question'   => $request->question,
            'status'     => 'pending',
        ]);

        return response()->json([
            'message' => 'Pertanyaan berhasil dikirim',
            'data'    => $question
        ], 201);
    }
}
