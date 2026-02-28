<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class StudentQuestionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Question::where('student_id', $user->student->id);

        if (request('filter') == 'pending') {
            $query->where('status', 'pending');
        }

        if (request('filter') == 'answered') {
            $query->where('status', 'answered');
        }

        $questions = $query->latest()->paginate(10);

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

    /* =========================
       CREATE PAGE
    ========================== */
    public function create()
    {
        $teachers = User::where('role', 'guru')->get();

        return view('student.faq.create', compact('teachers'));
    }

    /* =========================
       STORE
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'question' => 'required|string|max:1000',
        ]);

        Question::create([
            'student_id' => auth()->user()->student->id,
            'teacher_id' => $request->teacher_id,
            'title' => $request->title,
            'question' => $request->question,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('student.questions.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function show(Question $question)
    {
        $this->authorizeQuestion($question);

        return response()->json($question->load('answer'));
    }

    /* =========================
       UPDATE (EDIT TITLE + QUESTION)
    ========================== */
    public function update(Request $request, Question $question)
    {
        $this->authorizeQuestion($question);

        if ($question->status === 'answered') {
            return back()->with('error', 'Pertanyaan sudah dijawab dan tidak bisa diedit.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string|max:1000',
        ]);

        $question->update([
            'title' => $request->title,
            'question' => $request->question,
        ]);

        return redirect()
            ->route('student.questions.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /* =========================
       DELETE (HANYA JIKA BELUM DIJAWAB)
    ========================== */
    public function destroy(Question $question)
    {
        $this->authorizeQuestion($question);

        if ($question->status === 'answered') {
            return back()->with('error', 'Pertanyaan sudah dijawab dan tidak bisa dihapus.');
        }

        $question->delete();

        return redirect()
            ->route('student.questions.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    private function authorizeQuestion($question)
    {
        if ($question->student_id !== auth()->user()->student->id) {
            abort(403);
        }
    }
}
