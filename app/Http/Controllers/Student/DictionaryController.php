<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dictionary;

class DictionaryController extends Controller
{
    public function index(Request $request)
    {
        $letter = $request->get('letter', 'A');

        $words = Dictionary::where('term', 'like', $letter.'%')
                    ->orderBy('term')
                    ->paginate(15);

        return view('student.dictionaries.index', compact('words','letter'));
    }
}

