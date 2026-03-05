<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\Approval;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with(['gradeCategory', 'subjectCategory', 'teacher', 'contents', 'approval', 'kelas', 'kelasList']);

        if (auth()->user()->role === 'guru') {
            $query->where('teacher_id', auth()->id());
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->whereHas('approval', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $modules = $query->latest()->paginate(10);
        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';

        $pendingCount = Approval::where('status', 'pending')->count();

        return view("{$rolePrefix}.modules.index", compact('modules', 'pendingCount'));
    }

    public function create()
    {
        $grades   = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $teachers = \App\Models\User::where('role', 'guru')->get();

        $kelas = collect();
        if (auth()->user()->role === 'guru') {
            $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();
            $kelas   = $teacher ? $teacher->kelas : collect();
        }

        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';
        return view("{$rolePrefix}.modules.create", compact('grades', 'subjects', 'teachers', 'kelas'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title'               => 'required|string|max:255',
            'desc'                => 'required|string',
            'track'               => 'required|in:BE,FE',
            'grade_category_id'   => 'nullable|exists:grade_categories,id',
            'subject_category_id' => 'required|exists:subject_categories,id',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['teacher_id'] = 'required|exists:users,id';
        }

        if (auth()->user()->role === 'guru') {
            // ✅ Validasi array kelas_ids (wajib pilih minimal 1)
            $rules['kelas_ids']   = 'required|array|min:1';
            $rules['kelas_ids.*'] = 'exists:kelas,id';
        }

        $validated = $request->validate($rules);

        if (auth()->user()->role === 'guru') {
            $validated['teacher_id'] = auth()->id();

            // ✅ Proteksi: pastikan semua kelas_ids adalah kelas yang diampu guru
            $teacher      = \App\Models\Teacher::where('user_id', auth()->id())->first();
            $allowedKelas = $teacher ? $teacher->kelas->pluck('id')->toArray() : [];
            $requestedIds = $request->kelas_ids;

            $invalid = array_diff($requestedIds, $allowedKelas);
            if (!empty($invalid)) {
                return back()->withErrors(['kelas_ids' => 'Kamu tidak berhak upload modul ke salah satu kelas yang dipilih!'])->withInput();
            }
        } else {
            $validated['teacher_id'] = $request->teacher_id;
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['like']         = 0;

        // Hapus kelas_ids dari validated sebelum create (bukan kolom di tabel modules)
        $kelasIds = $validated['kelas_ids'] ?? [];
        unset($validated['kelas_ids']);

        $module = \App\Models\Module::create($validated);

        // ✅ Simpan relasi many-to-many ke tabel module_kelas
        if (!empty($kelasIds)) {
            $module->kelasList()->sync($kelasIds);
        }

        Approval::create([
            'module_id'  => $module->id,
            'teacher_id' => $module->teacher_id,
            'status'     => 'pending',
            'comment'    => null,
        ]);

        $rolePrefix = auth()->user()->role === 'guru' ? 'teacher' : 'admin';
        return redirect()->route("{$rolePrefix}.modules.index")
            ->with('success', 'Modul berhasil dibuat & status saat ini adalah Pending untuk direview Admin!');
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

        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';
        return view("{$rolePrefix}.modules.content_detail", compact('content', 'playlist', 'prev', 'next'));
    }

    public function edit($id)
    {
        $module   = \App\Models\Module::with('kelasList')->findOrFail($id);
        $grades   = \App\Models\GradeCategory::all();
        $subjects = \App\Models\SubjectCategory::all();
        $teachers = \App\Models\User::where('role', 'guru')->get();

        $kelas = collect();
        if (auth()->user()->role === 'guru') {
            $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();
            $kelas   = $teacher ? $teacher->kelas : collect();
        }

        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';
        return view("{$rolePrefix}.modules.edit", compact('module', 'grades', 'subjects', 'teachers', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title'               => 'required|string|max:255',
            'desc'                => 'required|string',
            'track'               => 'required|string',
            'grade_category_id'   => 'nullable|exists:grade_categories,id',
            'subject_category_id' => 'required|exists:subject_categories,id',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['teacher_id'] = 'required|exists:users,id';
        }

        if (auth()->user()->role === 'guru') {
            $rules['kelas_ids']   = 'required|array|min:1';
            $rules['kelas_ids.*'] = 'exists:kelas,id';
        }

        $validated = $request->validate($rules);

        $module = \App\Models\Module::findOrFail($id);

        if (auth()->user()->role === 'guru') {
            // Proteksi kelas
            $teacher      = \App\Models\Teacher::where('user_id', auth()->id())->first();
            $allowedKelas = $teacher ? $teacher->kelas->pluck('id')->toArray() : [];
            $invalid      = array_diff($request->kelas_ids, $allowedKelas);

            if (!empty($invalid)) {
                return back()->withErrors(['kelas_ids' => 'Kamu tidak berhak memilih salah satu kelas tersebut!'])->withInput();
            }

            $kelasIds = $validated['kelas_ids'];
            unset($validated['kelas_ids']);
            $module->kelasList()->sync($kelasIds);
        } else {
            $validated['teacher_id'] = $request->teacher_id;
        }

        $module->update($validated);

        if ($module->approval && in_array($module->approval->status, ['revisi', 'rejected'])) {
            $module->approval->update(['status' => 'pending', 'comment' => null]);
        }

        $rolePrefix = auth()->user()->role === 'guru' ? 'teacher' : 'admin';
        return redirect()->route("{$rolePrefix}.modules.index")
            ->with('success', 'Modul "' . $module->title . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $module = \App\Models\Module::findOrFail($id);
        $module->delete();

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

        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';
        return view("{$rolePrefix}.modules.add_content", compact('module', 'contents'));
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

        $data              = $request->only(['title', 'content', 'order']);
        $data['module_id'] = $id;
        $data['order']     = $lastOrder ? $lastOrder + 1 : 1;

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
        $moduleId = $content->module_id;
        $content->delete();

        return redirect()
            ->route('admin.modules.addContent', $moduleId)
            ->with('success', 'Sub materi berhasil dihapus!');
    }

    public function editContent(ModuleContent $content)
    {
        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';
        return view('admin.modules.content_edit', compact('content'));
    }

    public function updateContent(Request $request, ModuleContent $content)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        $content->update($request->only('title', 'content', 'video_url'));

        $rolePrefix = in_array(auth()->user()->role, ['guru', 'teacher']) ? 'teacher' : 'admin';
        return redirect()
            ->route('admin.modules.content.show', $content->id)
            ->with('success', 'Sub-Materi berhasil diupdate!');
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:approved,revisi,rejected',
            'comment' => 'nullable|string'
        ]);

        $module = \App\Models\Module::findOrFail($id);

        $dataToUpdate = [
            'teacher_id' => $module->teacher_id,
            'status'     => $request->status,
            'comment'    => $request->comment
        ];

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

    public function approveAll(Request $request)
    {
        $updated = \App\Models\Approval::where('status', 'pending')
            ->update([
                'status'      => 'approved',
                'comment'     => null,
                'approved_at' => now(),
            ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Berhasil menyetujui {$updated} modul sekaligus!",
                'count'   => $updated,
            ]);
        }

        return redirect()->route('admin.modules.index')
            ->with('success', "Berhasil menyetujui {$updated} modul pending sekaligus!");
    }
}
