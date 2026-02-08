<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Ambil user beserta profil student-nya
        $users = User::with('student')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role'     => 'required|in:siswa,guru,admin', // Sesuaikan enum lo
            'name'     => 'required_if:role,siswa',
            'nis'      => 'required_if:role,siswa',
            'kelas'    => 'required_if:role,siswa',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            if ($request->role == 'siswa') {
                Student::create([
                    'user_id' => $user->id,
                    'nis'     => $request->nis,
                    'name'    => $request->name,
                    'kelas'   => $request->kelas,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete(); // Cascade akan otomatis hapus data di tabel student
        return back()->with('success', 'User berhasil dihapus!');
    }
}
