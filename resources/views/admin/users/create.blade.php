@extends('layouts.app')

@section('content')
<br><br><br>

<div class="content-body px-4">
    <div class="max-w-900 mx-auto">
        <h3 class="fw-800 text-dark mb-4">Tambah User Baru</h3>

        <div class="main-form-card">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                {{-- ===================== --}}
                {{-- SECTION: AKUN LOGIN --}}
                {{-- ===================== --}}
                <h6 class="section-title">AKUN LOGIN</h6>

                {{-- Username --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Username</label>
                    <div class="col-sm-9">
                        <input type="text" name="username" value="{{ old('username') }}" class="input-modern" placeholder="Contoh: rpl_user123" required>
                    </div>
                </div>

                {{-- Password --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Password</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" class="input-modern" placeholder="Minimal 6 karakter" required>
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
                            <option value="siswa" {{ old('role')=='siswa'?'selected':'' }}>Siswa</option>
                            <option value="guru"  {{ old('role')=='guru'?'selected':'' }}>Guru</option>
                            <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                        </select>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- FIELD NAME (Siswa + Guru) --}}
                {{-- ===================== --}}
                <div id="nameField">
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{ old('name') }}" class="input-modern" placeholder="Masukkan nama lengkap">
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
                            <input type="text" name="nis" value="{{ old('nis') }}" class="input-modern" placeholder="Nomor Induk Siswa">
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Kelas</label>
                        <div class="col-sm-6">
                            <input type="text" name="kelas" value="{{ old('kelas') }}" class="input-modern" placeholder="Contoh: XII RPL 1">
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
                            <input type="text" name="nip" value="{{ old('nip') }}" class="input-modern" placeholder="Nomor Induk Pegawai">
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- FOOTER BUTTON --}}
                {{-- ===================== --}}
                <div class="form-footer mt-4">
                    <button type="submit" class="btn-save-modern">
                        Simpan User
                    </button>

                    <a href="{{ route('admin.users.index', ['role' => request('role', 'siswa')]) }}" class="btn-cancel-modern">
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
        // Admin
        nameDiv.style.display    = "none";
        studentDiv.style.display = "none";
        teacherDiv.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", toggleFields);
</script>

@endsection
