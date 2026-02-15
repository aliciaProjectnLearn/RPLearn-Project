<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dictionary;

class DictionaryController extends Controller
{
    public function index()
    {
        $dictionaries = Dictionary::all();
        return view('teacher.dictionaries.index', compact('dictionaries'));
    }

    public function show($id)
    {
        $dictionary = Dictionary::findOrFail($id);
        return view('teacher.dictionaries.show', compact('dictionary'));
    }
    
    public function create()
    {
        return view('teacher.dictionaries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'term' => 'required|string|max:255|unique:dictionaries,term',
            'definition' => 'required|string'
        ], [
            'term.unique' => 'Istilah sudah ada!'
        ]);

        Dictionary::create($request->all());

        return redirect()->route('teacher.dictionaries.index')
                        ->with('success','Istilah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $dictionary = Dictionary::findOrFail($id);
        return view('teacher.dictionaries.create', compact('dictionary'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'term' => 'required|string|max:255|unique:dictionaries,term,' . $id,
            'definition' => 'required|string'
        ], [
            'term.unique' => 'Istilah sudah ada!'
        ]);

        $dictionary = Dictionary::findOrFail($id);
        $dictionary->update($request->all());

        return redirect()->route('teacher.dictionaries.index')
                        ->with('success','Istilah berhasil diupdate');
    }


    public function destroy($id)
    {
        $dictionary = Dictionary::findOrFail($id);
        $dictionary->delete();
        return redirect()->route('teacher.dictionaries.index')->with('success', 'Istilah berhasil dihapus!');
    }
}
