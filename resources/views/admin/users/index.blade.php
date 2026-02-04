@extends('layouts.app')

@section('content')
<br><br><br> {{-- Jarak sakti lo --}}

<div class="content-body px-4">
    <div class="container-fluid">

        {{-- Header & Tombol Tambah --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <br>
                <h3 class="fw-800 text-dark m-0">Manajemen User</h3>
                <br>
                <p class="text-muted small">Total: <span class="text-orange fw-bold">{{ $users->count() }} Akun Terdaftar</span></p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-save-modern text-decoration-none">
                <br>
                <hr>
                <i class="fa-solid fa-user-plus me-2"></i> Tambah User
            </a>
        </div>

        {{-- Tabel User Modern --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-800 text-secondary">User & Role</th>
                            <th class="py-3 text-uppercase small fw-800 text-secondary">Detail Profil</th>
                            <th class="py-3 text-uppercase small fw-800 text-secondary">Tanggal Join</th>
                            <th class="text-end pe-4 py-3 text-uppercase small fw-800 text-secondary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr style="border-bottom: 1px solid #f1f4f8;">
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->username }}</div>
                                        <span class="badge {{ $user->role == 'admin' ? 'bg-danger-subtle text-danger' : ($user->role == 'guru' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }} rounded-pill small">
                                            {{ strtoupper($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role == 'siswa' && $user->student)
                                    <div class="small">
                                        <div class="fw-bold text-dark">{{ $user->student->name }}</div>
                                        <div class="text-muted">NIS: {{ $user->student->nis }} | Kelas: {{ $user->student->kelas }}</div>
                                    </div>
                                @else
                                    <span class="text-muted small italic">N/A (Bukan Siswa)</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Tombol Edit (Bisa lo tambahin routenya nanti) --}}
                                    <a href="#" class="btn-action text-primary" title="Edit User">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action text-danger border-0 bg-transparent">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
