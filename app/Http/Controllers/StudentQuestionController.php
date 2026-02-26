<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class StudentQuestionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Question::where('student_id', $user->student->id);

        // FILTER
        if (request('filter') == 'pending') {
            $query->where('status', 'pending');
        }

        if (request('filter') == 'answered') {
            $query->where('status', 'answered');
        }

        $questions = $query->latest()->get();

        $totalCount = Question::where('student_id', $user->student->id)->count();

        $pendingCount = Question::where('student_id', $user->student->id)
            ->where('status', 'pending')
            ->count();

        $answeredCount = Question::where('student_id', $user->student->id)
            ->where('status', 'answered')
            ->count();

        return view('student.faq.question', compact(
            'questions',
            'totalCount',
            'pendingCount',
            'answeredCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        Question::create([
            'student_id' => auth()->user()->student->id,
            'question' => $request->question,
            'status' => 'pending',
        ]);

        return redirect()->route('student.questions.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function show(Question $question)
    {
        $this->authorizeQuestion($question);

        return response()->json($question->load('answer'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorizeQuestion($question);

        if ($question->status === 'answered') {
            return back()->with('error', 'Pertanyaan sudah dijawab dan tidak bisa diedit.');
        }

        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        $question->update([
            'question' => $request->question,
        ]);

        return redirect()->route('student.questions.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        $this->authorizeQuestion($question);

        $question->delete();

        return redirect()->route('student.questions.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    private function authorizeQuestion($question)
    {
        if ($question->student_id !== auth()->user()->student->id) {
            abort(403);
        }
    }
}
