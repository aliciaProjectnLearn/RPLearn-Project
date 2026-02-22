<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\Approval; // <-- Perbaikan nama model (Biasanya huruf besar)
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with(['gradeCategory', 'subjectCategory', 'teacher', 'contents', 'approval']);

        if (auth()->user()->role === 'guru'){
            $query->where('teacher_id', auth()->id());
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->whereHas('approval', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $modules = $query->latest()->paginate(10);

        $rolePrefix = auth()->user()->role === 'guru' ? 'teacher' : 'admin';

        return view("{$rolePrefix}.modules.index", compact('modules'));
    }

    public function create()
    {
        $grades = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $teachers = \App\Models\User::where('role', 'guru')->get();

        return view('admin.modules.create', compact('grades', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        // 1. Validasi dinamis (teacher_id hanya wajib diisi kalau yang login admin)
        $rules = [
            'title'               => 'required|string|max:255',
            'desc'                => 'required|string',
            'track'               => 'required|in:BE,FE',
            'grade_category_id'   => 'required|exists:grade_categories,id',
            'subject_category_id' => 'required|exists:subject_categories,id',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['teacher_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // 2. Set teacher_id otomatis kalau yang login guru
        if (auth()->user()->role === 'guru') {
            $validated['teacher_id'] = auth()->id();
        } else {
            $validated['teacher_id'] = $request->teacher_id;
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['like'] = 0;

        $module = \App\Models\Module::create($validated);

        Approval::create([
            'module_id'  => $module->id,
            'teacher_id' => $module->teacher_id,
            'status'     => 'pending',
            'comment'    => null,
        ]);

        $rolePrefix = auth()->user()->role === 'guru' ? 'teacher' : 'admin';
        return redirect()->route("{$rolePrefix}.modules.index")->with('success', 'Modul berhasil dibuat & status saat ini adalah Pending untuk direview Admin!');
    }

    public function showContent(ModuleContent $content)
    {
        $playlist = ModuleContent::where('module_id', $content->module_id)
            ->orderBy('id')
            ->get();

        $currentIndex = $playlist->search(function ($item) use ($content) {
            return $item->id === $content->id;
        });

        $prev = $playlist[$currentIndex - 1] ?? null;
        $next = $playlist[$currentIndex + 1] ?? null;

        return view('admin.modules.content_detail', compact(
            'content', 'playlist', 'prev', 'next'
        ));
    }

    public function edit($id)
    {
        $module = \App\Models\Module::findOrFail($id);
        $grades = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $teachers = \App\Models\User::where('role', 'guru')->get();

        // Bisa ditambahkan pengecekan role prefix jika view edit guru berbeda tempatnya
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

        if ($module->approval && in_array($module->approval->status, ['revisi', 'rejected'])) {
            $module->approval->update(['status' => 'pending', 'comment' => null]);
        }

        // FOKUS PERBAIKAN: Redirect sesuai role
        $rolePrefix = auth()->user()->role === 'guru' ? 'teacher' : 'admin';
        return redirect()->route("{$rolePrefix}.modules.index")
            ->with('success', 'Modul "' . $module->title . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $module = \App\Models\Module::findOrFail($id);
        $module->delete();

        // FOKUS PERBAIKAN: Redirect sesuai role
        $rolePrefix = auth()->user()->role === 'guru' ? 'teacher' : 'admin';
        return redirect()->route("{$rolePrefix}.modules.index")
            ->with('success', 'Modul berhasil dihapus dari sistem!');
    }

    public function addContent(Request $request, $id)
    {
        $module = \App\Models\Module::findOrFail($id);

        $query = ModuleContent::where('module_id', $id)->orderBy('order');

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
            'file_path' => 'nullable|file|mimes:pdf|max:20000',
        ]);

        $lastOrder = ModuleContent::where('module_id', $id)->max('order');

        $data = $request->only(['title', 'content', 'order']);
        $data['module_id'] = $id;
        $data['order'] = $lastOrder ? $lastOrder + 1 : 1;

        if ($request->hasFile('file_path')) {
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
        // Menyimpan ID module sebelum content dihapus untuk keperluan redirect
        $moduleId = $content->module_id;
        $content->delete();

        return redirect()
            ->route('admin.modules.addContent', $moduleId) // Redirect ke list content modul tersebut
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

    /**
     * TUGAS CARD 21: Proses review modul oleh admin (Approved/Revisi/Rejected)
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:approved,revisi,rejected',
            'comment' => 'nullable|string'
        ]);

        $module = \App\Models\Module::findOrFail($id);

        // Menyiapkan data yang akan disimpan
        $dataToUpdate = [
            'teacher_id' => $module->teacher_id,
            'status'     => $request->status,
            'comment'    => $request->comment
        ];

        // LOGIKA TAMBAHAN UNTUK CHECKLIST TRELLO: Catat waktu approve!
        if ($request->status === 'approved') {
            $dataToUpdate['approved_at'] = now();
        } else {
            $dataToUpdate['approved_at'] = null;
        }

        \App\Models\Approval::updateOrCreate(
            ['module_id' => $module->id],
            $dataToUpdate
        );

        return redirect()->route('admin.modules.index')
            ->with('success', 'Status persetujuan modul "' . $module->title . '" berhasil diperbarui!');
    }
}
