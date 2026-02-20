<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $search = $request->query('search');

        $questions = Question::with(['student', 'module', 'answer'])
            ->when($filter === 'pending', function ($query) {
                $query->where('status', 'pending');
            })
            ->when($filter === 'answered', function ($query) {
                $query->where('status', 'answered');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('question', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($student) use ($search) {
                        $student->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->get();

        // Counter global (tetap global)
        $pendingCount = Question::where('status', 'pending')->count();
        $answeredCount = Question::where('status', 'answered')->count();
        $totalCount = Question::count();

        return view('teacher.faq.index', compact(
            'questions',
            'filter',
            'search',
            'pendingCount',
            'answeredCount',
            'totalCount'
        ));
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

    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);

        $answer->update([
            'answer' => $request->answer
        ]);

        return back()->with('success', 'Jawaban berhasil diperbarui.');
    }

    public function destroy(Answer $answer)
    {
        $question = $answer->question;

        $answer->delete();

        // Balikin status ke pending
        $question->update([
            'status' => 'pending'
        ]);

        return back()->with('success', 'Jawaban berhasil dihapus.');
    }


}
