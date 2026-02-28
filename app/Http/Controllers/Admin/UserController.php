<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role   = $request->role ?? 'siswa';
        $search = $request->search;

        $users = User::with(['student', 'teacher'])
            ->where('role', $role)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('username', 'like', "%$search%")
                        ->orWhereHas('student', function ($s) use ($search) {
                            $s->where('name', 'like', "%$search%")
                                ->orWhere('nis', 'like', "%$search%");
                        })
                        ->orWhereHas('teacher', function ($t) use ($search) {
                            $t->where('name', 'like', "%$search%")
                                ->orWhere('nip', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'role'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('admin.users.create', compact('kelas'));
    }

    public function edit($id)
    {
        $user  = User::with(['student', 'teacher.kelas'])->findOrFail($id);
        $kelas = Kelas::orderBy('nama')->get();
        return view('admin.users.edit', compact('user', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'  => 'required|unique:users,username',
            'password'  => 'required|min:6',
            'role'      => 'required|in:siswa,guru,admin',
            'name'      => 'required_if:role,siswa,guru',
            'nis'       => 'required_if:role,siswa',
            'kelas'     => 'required_if:role,siswa',
            'nip'       => 'required_if:role,guru',
            'kelas_ids' => 'nullable|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            if ($request->role === 'siswa') {
                Student::create([
                    'user_id' => $user->id,
                    'name'    => $request->name,
                    'nis'     => $request->nis,
                    'kelas'   => $request->kelas,
                    'kelas_id' => $request->kelas_id ?: null,
                ]);
            }

            if ($request->role === 'guru') {
                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'name'    => $request->name,
                    'nip'     => $request->nip,
                ]);

                // ✅ Sync kelas yang diampu (many-to-many)
                if ($request->filled('kelas_ids')) {
                    $teacher->kelas()->sync($request->kelas_ids);
                }
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('toast_success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::with(['student', 'teacher'])->findOrFail($id);

        $request->validate([
            'username'    => 'required|unique:users,username,' . $user->id,
            'role'        => 'required|in:siswa,guru,admin',
            'name'        => 'required_if:role,siswa,guru',
            'nis'         => 'required_if:role,siswa',
            'kelas'       => 'required_if:role,siswa',
            'nip'         => 'required_if:role,guru',
            'kelas_ids'   => 'nullable|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'username' => $request->username,
                'role'     => $request->role,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if ($request->role === 'siswa') {
                $user->teacher()?->delete();
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $request->name,
                        'nis' => $request->nis,
                        'kelas' => $request->kelas,
                        'kelas_id' => $request->kelas_id ?: null,
                    ]
                );
            }

            if ($request->role === 'guru') {
                $user->student()?->delete();
                $teacher = Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    ['name' => $request->name, 'nip' => $request->nip]
                );

                // ✅ Sync kelas yang diampu (many-to-many)
                $teacher->kelas()->sync($request->kelas_ids ?? []);
            }

            if ($request->role === 'admin') {
                $user->student()?->delete();
                $user->teacher()?->delete();
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('toast_success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('toast_success', 'User berhasil dihapus!');
    }
}
