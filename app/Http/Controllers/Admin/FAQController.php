<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.faq.index', compact('questions'));
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
