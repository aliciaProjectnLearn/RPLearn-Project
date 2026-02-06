<?php

namespace App\Http\Controllers\Admin; // Namespace sudah sesuai folder

use App\Http\Controllers\Controller;
use App\Models\Dictionary;
use App\Models\Module;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    /**
     * Menampilkan daftar istilah dengan fitur Search dari foto lo
     */
    public function index(Request $request)
    {
        $query = Dictionary::with('module');

        // Fitur Search yang lo mau
        if ($request->filled('search')) {
            $query->where('term', 'like', '%' . $request->search . '%');
        }

        $dictionaries = $query->latest()->get();

        // Return ke view ADMIN, bukan student lagi
        return view('admin.dictionaries.index', compact('dictionaries'));
    }

    public function create()
    {
        $modules = Module::all();
        return view('admin.dictionaries.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        Dictionary::create($request->all());
        return redirect()->route('admin.dictionaries.index')->with('success', 'Istilah baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $dictionary = Dictionary::findOrFail($id);
        $modules = Module::all();
        return view('admin.dictionaries.create', compact('dictionary', 'modules'));
    }

    public function update(Request $request, $id)
    {
        $dictionary = Dictionary::findOrFail($id);
        $request->validate([
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        $dictionary->update($request->all());
        return redirect()->route('admin.dictionaries.index')->with('success', 'Istilah berhasil diperbarui!');
    }

    public function show($id)
    {
        $dictionary = Dictionary::findOrFail($id);
        return view('admin.dictionaries.show', compact('dictionary'));
    }
 
    public function destroy($id)
    {
        $dictionary = Dictionary::findOrFail($id);
        $dictionary->delete();
        return redirect()->route('admin.dictionaries.index')->with('success', 'Istilah berhasil dihapus dari sistem!');
    }
}
