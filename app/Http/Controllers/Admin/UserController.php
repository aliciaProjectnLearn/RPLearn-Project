<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Default role siswa kalau belum pilih filter
        $role   = $request->role ?? 'siswa';
        $search = $request->search;

        $users = User::with(['student', 'teacher'])

            // ✅ Filter Role wajib jalan
            ->where('role', $role)

            // ✅ Search aman
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
        return view('admin.users.create');
    }

    public function edit($id)
    {
        $user = User::with(['student', 'teacher'])->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    // ===============================
    // ✅ STORE (Tambah User)
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role'     => 'required|in:siswa,guru,admin',

            // siswa wajib isi
            'name'     => 'required_if:role,siswa,guru',
            'nis'      => 'required_if:role,siswa',
            'kelas'    => 'required_if:role,siswa',

            // guru wajib isi
            'nip'      => 'required_if:role,guru',
        ]);

        DB::transaction(function () use ($request) {

            // ✅ Simpan akun login
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            // ✅ Kalau siswa
            if ($request->role === 'siswa') {
                Student::create([
                    'user_id' => $user->id,
                    'name'    => $request->name,
                    'nis'     => $request->nis,
                    'kelas'   => $request->kelas,
                ]);
            }

            // ✅ Kalau guru
            if ($request->role === 'guru') {
                Teacher::create([
                    'user_id' => $user->id,
                    'name'    => $request->name,
                    'nip'     => $request->nip,
                ]);
            }

            // ✅ Kalau admin → cukup users saja
        });

        return redirect()
            ->route('admin.users.index')
            ->with('toast_success', 'User berhasil ditambahkan!');
    }

    // ===============================
    // ✅ UPDATE (Edit User)
    // ===============================
    public function update(Request $request, $id)
    {
        $user = User::with(['student', 'teacher'])->findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'role'     => 'required|in:siswa,guru,admin',

            'name'     => 'required_if:role,siswa,guru',
            'nis'      => 'required_if:role,siswa',
            'kelas'    => 'required_if:role,siswa',

            'nip'      => 'required_if:role,guru',
        ]);

        DB::transaction(function () use ($request, $user) {

            // ✅ Update user login
            $user->update([
                'username' => $request->username,
                'role'     => $request->role,
            ]);

            // ✅ Kalau password diisi baru update
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // ==========================
            // ✅ ROLE SWITCH CLEANUP
            // ==========================

            // Kalau jadi siswa → hapus teacher lama
            if ($request->role === 'siswa') {
                $user->teacher()?->delete();

                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name'  => $request->name,
                        'nis'   => $request->nis,
                        'kelas' => $request->kelas,
                    ]
                );
            }

            // Kalau jadi guru → hapus student lama
            if ($request->role === 'guru') {
                $user->student()?->delete();

                Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $request->name,
                        'nip'  => $request->nip,
                    ]
                );
            }

            // Kalau jadi admin → hapus semua profil
            if ($request->role === 'admin') {
                $user->student()?->delete();
                $user->teacher()?->delete();
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('toast_success', 'User berhasil diupdate!');
    }

    // ===============================
    // ✅ DELETE
    // ===============================
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('toast_success', 'User berhasil dihapus!');
    }
}
