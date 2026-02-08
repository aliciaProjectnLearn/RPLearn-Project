@extends('layouts.app')

@section('content')
<br><br><br> {{-- Jarak sakti biar gak nyelam --}}

<div class="content-body px-4">
    <div class="max-w-900 mx-auto">
        <h3 class="fw-800 text-dark mb-4">Tambah User Baru</h3>

        <div class="main-form-card">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <h6 class="section-title">AKUN LOGIN</h6>
                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Username</label>
                    <div class="col-sm-9">
                        <input type="text" name="username" class="input-modern" placeholder="Contoh: rpl_user123" required>
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Password</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" class="input-modern" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Role (Jabatan)</label>
                    <div class="col-sm-6">
                        <select name="role" id="roleSelect" class="input-modern" onchange="toggleStudentFields()" required>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                {{-- Field Khusus Siswa (Muncul jika Role = Siswa) --}}
                <div id="studentFields">
                    <h6 class="section-title mt-5">PROFIL SISWA</h6>
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="input-modern" placeholder="Nama sesuai ijazah">
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">NIS</label>
                        <div class="col-sm-6">
                            <input type="text" name="nis" class="input-modern" placeholder="Nomor Induk Siswa">
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Kelas</label>
                        <div class="col-sm-6">
                            <input type="text" name="kelas" class="input-modern" placeholder="Contoh: XII RPL 1">
                        </div>
                    </div>
                </div>

                <div class="form-footer mt-4">
                    <button type="submit" class="btn-save-modern">Simpan User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel-modern">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleStudentFields() {
        const role = document.getElementById('roleSelect').value;
        const studentDiv = document.getElementById('studentFields');
        studentDiv.style.display = (role === 'siswa') ? 'block' : 'none';
    }
    // Jalankan saat load pertama kali
    document.addEventListener('DOMContentLoaded', toggleStudentFields);
</script>
@endsection
