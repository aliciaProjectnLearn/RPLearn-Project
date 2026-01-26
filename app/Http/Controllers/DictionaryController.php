<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Dictionary::query();

        // Search
        if ($request->filled('search')) {
            $query->where('term', 'like', '%' . $request->search . '%');
        }

        // Sorting A-Z
        $dictionaries = $query->orderBy('term', 'asc')->limit(6)->get();

        return view('dashboard.student', compact('dictionaries'));
    }
}
