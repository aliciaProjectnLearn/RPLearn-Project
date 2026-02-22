<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
$query = Module::with(['gradeCategory', 'subjectCategory', 'teacher', 'contents']);

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $modules = $query->latest()->paginate(10);

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
    public function showContent(ModuleContent $content)
    {
        $playlist = ModuleContent::where('module_id', $content->module_id)
            ->orderBy('id')
            ->get();

        // Cari index materi sekarang
        $currentIndex = $playlist->search(function ($item) use ($content) {
            return $item->id === $content->id;
        });

        // Prev & Next
        $prev = $playlist[$currentIndex - 1] ?? null;
        $next = $playlist[$currentIndex + 1] ?? null;

        return view('admin.modules.content_detail', compact(
            'content',
            'playlist',
            'prev',
            'next'
        ));
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

    public function addContent(Request $request, $id)
    {
        $module = \App\Models\Module::findOrFail($id);

        $query = ModuleContent::where('module_id', $id)
            ->orderBy('order');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $contents = $query->get();

        return view('admin.modules.add_content', compact('module', 'contents'));
    }

    public function storeContent(Request $request, $id)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'video_url' => 'nullable|url',
            'file_path' => 'nullable|file|mimes:pdf|max:20000', // Max 20MB
        ]);

        $lastOrder = ModuleContent::where('module_id', $id)->max('order');

        $data = $request->only(['title', 'content', 'order']);
        $data['module_id'] = $id;
        $data['order'] = $lastOrder ? $lastOrder + 1 : 1;

        if ($request->hasFile('file_path')) {
            // Simpan PDF ke folder public
            $data['file_path'] = $request->file('file_path')->store('modules/pdf', 'public');
        }

        if ($request->video_url) {
            $data['video_url'] = str_replace("watch?v=", "embed/", $request->video_url);
        }

        \App\Models\ModuleContent::create($data);

        return back()->with('success', 'Konten materi "' . $request->title . '" berhasil ditambahkan!');
    }

    public function destroyContent(ModuleContent $content)
    {
        $content->delete();

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Sub materi berhasil dihapus bro 🔥');
    }

    public function editContent(ModuleContent $content)
    {
        return view('admin.modules.content_edit', compact('content'));
    }

    public function updateContent(Request $request, ModuleContent $content)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        $content->update($request->only('title','content','video_url'));

        return redirect()
            ->route('admin.modules.content.show', $content->id)
            ->with('success', 'Sub-Materi berhasil diupdate bro 🔥');
    }
}
