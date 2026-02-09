<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Halaman utama FAQ (TABLE)
     */
    public function index(Request $request)
    {
        $questions = Question::with([
                'student',
                'teacher',
                'answer.teacher'
            ])
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                      ->orWhere('question', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.faq.index', compact('questions'));
    }

    /**
     * Detail FAQ (JSON → buat popup/modal)
     */
    public function show($id)
    {
        $question = Question::with([
                'student',
                'teacher',
                'answer.teacher'
            ])->findOrFail($id);

        return response()->json($question);
    }
}
