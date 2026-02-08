<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data modul beserta relasi sesuai ERD
        $modules = \App\Models\Module::with(['gradeCategory', 'subjectCategory', 'teacher'])->get();

        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil data kategori untuk isi dropdown
        $grades = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $teachers = \App\Models\User::where('role', 'guru')->get();

        // Pastikan variabel ini di-compact ke view
        return view('admin.modules.create', compact('grades', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'desc'                => 'required|string',
            'track'               => 'required|in:BE,FE', // Validasi hanya boleh BE atau FE
            'grade_category_id'   => 'required|exists:grade_categories,id',
            'subject_category_id' => 'required|exists:subject_categories,id',
            'teacher_id'          => 'required|exists:users,id',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['like'] = 0;

        \App\Models\Module::create($validated);

        return redirect()->route('admin.modules.index')->with('success', 'Modul berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $module = \App\Models\Module::findOrFail($id);
        $grades = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $teachers = \App\Models\User::where('role', 'guru')->get();

        return view('admin.modules.edit', compact('module', 'grades', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'desc'                => 'required|string',
            'track'               => 'required|string',
            'grade_category_id'   => 'required|exists:grade_categories,id',
            'subject_category_id' => 'required|exists:subject_categories,id',
            'teacher_id'          => 'required|exists:users,id',
        ]);

        $module = \App\Models\Module::findOrFail($id);
        $module->update($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Modul "' . $module->title . '" berhasil diperbarui!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $module = \App\Models\Module::findOrFail($id);
        $module->delete();
        return redirect()->route('admin.modules.index')
            ->with('success', 'Modul berhasil dihapus dari sistem!');
    }

    public function addContent($id)
{
    // Mengambil data modul beserta isi konten yang sudah ada
    $module = \App\Models\Module::with('contents')->findOrFail($id);
    return view('admin.modules.add_content', compact('module'));
}

    public function storeContent(Request $request, $id)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'video_url' => 'nullable|url',
            'file_path' => 'nullable|file|mimes:pdf|max:20000', // Max 20MB
        ]);

        $data = $request->only(['title', 'content', 'video_url']);
        $data['module_id'] = $id;

        if ($request->hasFile('file_path')) {
            // Simpan PDF ke folder public
            $data['file_path'] = $request->file('file_path')->store('modules/pdf', 'public');
        }

        \App\Models\ModuleContent::create($data);

        return back()->with('success', 'Konten materi "' . $request->title . '" berhasil ditambahkan!');
    }
}
