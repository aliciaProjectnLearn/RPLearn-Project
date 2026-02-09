@extends('layouts.app')

@section('content')
<br><br><br>

<div class="content-body px-4">
    <div class="max-w-900 mx-auto">
        <h3 class="fw-800 text-dark mb-4">Edit User</h3>

        <div class="main-form-card">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ===================== --}}
                {{-- SECTION: AKUN LOGIN --}}
                {{-- ===================== --}}
                <h6 class="section-title">AKUN LOGIN</h6>

                {{-- Username --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Username</label>
                    <div class="col-sm-9">
                        <input type="text"
                               name="username"
                               class="input-modern"
                               value="{{ old('username', $user->username) }}"
                               required>
                    </div>
                </div>

                {{-- Password Optional --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Password</label>
                    <div class="col-sm-9">
                        <input type="password"
                               name="password"
                               class="input-modern"
                               placeholder="Kosongkan jika tidak ingin diubah">
                    </div>
                </div>

                {{-- Role --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Role (Jabatan)</label>
                    <div class="col-sm-6">
                        <select name="role"
                                id="roleSelect"
                                class="input-modern"
                                onchange="toggleFields()"
                                required>

                            <option value="siswa" {{ $user->role=='siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru"  {{ $user->role=='guru' ? 'selected' : '' }}>Guru</option>
                            <option value="admin" {{ $user->role=='admin' ? 'selected' : '' }}>Admin</option>

                        </select>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- FIELD NAME (SISWA + GURU) --}}
                {{-- ===================== --}}
                <div id="nameField">
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text"
                                   name="name"
                                   class="input-modern"
                                   value="{{ old('name',
                                        $user->role=='siswa'
                                            ? ($user->student->name ?? '')
                                            : ($user->teacher->name ?? '')
                                   ) }}"
                                   placeholder="Nama lengkap">
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- SECTION: SISWA --}}
                {{-- ===================== --}}
                <div id="studentFields">
                    <h6 class="section-title mt-5">PROFIL SISWA</h6>

                    {{-- NIS --}}
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">NIS</label>
                        <div class="col-sm-6">
                            <input type="text"
                                   name="nis"
                                   class="input-modern"
                                   value="{{ old('nis', $user->student->nis ?? '') }}">
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Kelas</label>
                        <div class="col-sm-6">
                            <input type="text"
                                   name="kelas"
                                   class="input-modern"
                                   value="{{ old('kelas', $user->student->kelas ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- SECTION: GURU --}}
                {{-- ===================== --}}
                <div id="teacherFields" style="display:none;">
                    <h6 class="section-title mt-5">PROFIL GURU</h6>

                    {{-- NIP --}}
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">NIP</label>
                        <div class="col-sm-6">
                            <input type="text"
                                   name="nip"
                                   class="input-modern"
                                   value="{{ old('nip', $user->teacher->nip ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- FOOTER --}}
                {{-- ===================== --}}
                <div class="form-footer mt-4">
                    <button type="submit" class="btn-save-modern">
                        Update User
                    </button>

                    <a href="{{ route('admin.users.index') }}" class="btn-cancel-modern">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ===================== --}}
{{-- SCRIPT TOGGLE --}}
{{-- ===================== --}}
<script>
function toggleFields() {
    const role = document.getElementById("roleSelect").value;

    const nameDiv    = document.getElementById("nameField");
    const studentDiv = document.getElementById("studentFields");
    const teacherDiv = document.getElementById("teacherFields");

    if(role === "siswa"){
        nameDiv.style.display    = "block";
        studentDiv.style.display = "block";
        teacherDiv.style.display = "none";
    }
    else if(role === "guru"){
        nameDiv.style.display    = "block";
        studentDiv.style.display = "none";
        teacherDiv.style.display = "block";
    }
    else {
        nameDiv.style.display    = "none";
        studentDiv.style.display = "none";
        teacherDiv.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", toggleFields);
</script>

@endsection
