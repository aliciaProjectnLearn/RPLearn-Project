@extends('layouts.app')

@section('content')


<div class="content-body px-4">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">

            <div>
                <h3 class="fw-800 text-dark m-0">Manajemen User</h3>
                <p class="text-muted small mb-0">
                    Total:
                    <span class="fw-bold text-orange">
                        {{ $users->count() }} Akun Terdaftar
                    </span>
                </p>
            </div>

            {{-- Filter + Search --}}
            <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">

                {{-- Filter Role --}}
                <div class="user-actions">
                    <select name="role" onchange="this.form.submit()" class="form-select rounded-pill px-4 shadow-sm" style="min-width: 160px;">
                        <option value="siswa" {{ (request('role') ?? 'siswa') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru"  {{ (request('role') ?? 'siswa') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="admin" {{ (request('role') ?? 'siswa') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>

                    {{-- Search --}}
                    <div class="search-box">
                        <input type="text" id="searchInput" name="search" placeholder="Cari username / nama..." value="{{ request('search') }}" required>
                        <button type="submit" id="searchBtn" class="btn-search" disabled>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    {{-- Tambah --}}
                    <a href="{{ route('admin.users.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </form>
            </div>
        </div>


        {{-- Card Table --}}
        <div class="card border-0 shadow-lg rounded-4 bg-white p-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    {{-- Head --}}
                    <thead class="bg-light text-uppercase small text-secondary">
                        <tr>
                            <th style="min-width:180px;">Username</th>
                            <th style="min-width:110px;">Role</th>
                            <th style="min-width:200px;">Nama</th>

                            {{-- Kolom Dinamis --}}
                                @if(request('role', 'siswa') == 'siswa')
                                <th style="min-width:140px;">NIS</th>
                                <th style="min-width:140px;">Kelas</th>
                                @elseif(request('role', 'siswa') == 'guru')
                                <th style="min-width:160px;">NIP</th>
                            @endif

                            <th style="min-width:150px;">Tanggal Join</th>
                            <th class="text-end" style="min-width:120px;">Aksi</th>
                        </tr>
                    </thead>


                    {{-- Body --}}
                    <tbody>
                    @forelse($users as $user)

                        <tr>

                            {{-- Username --}}
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>

                                    <div class="user-name">
                                        {{ $user->username }}
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td>
                                <span class="badge rounded-pill px-3 py-2
                                    {{ $user->role=='admin' ? 'bg-danger' :
                                    ($user->role=='guru' ? 'bg-primary' : 'bg-success') }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>

                            {{-- Nama --}}
                            <td>
                                @if($user->role == 'siswa')
                                    {{ $user->student->name ?? '-' }}

                                @elseif($user->role == 'guru')
                                    {{ $user->teacher->name ?? '-' }}

                                @else
                                    ADMIN
                                @endif
                            </td>


                            {{-- Kolom Dinamis --}}
                            @if(request('role') == 'siswa')
                                <td>{{ $user->student->nis ?? '-' }}</td>
                                <td>{{ $user->student->kelas ?? '-' }}</td>

                            @elseif(request('role') == 'guru')
                                <td>{{ $user->teacher->nip ?? '-' }}</td>
                            @endif


                            {{-- Join --}}
                            <td class="text-muted small">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action text-primary" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <br>

                                    <hr>

                                    {{-- Delete --}}
                                    <br>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-action text-danger border-0 bg-transparent"
                                                title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Data user tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById("searchInput");
    const searchBtn = document.getElementById("searchBtn");

    function toggleButton() {
        searchBtn.disabled = searchInput.value.trim() === "";
    }

    searchInput.addEventListener("input", toggleButton);

    toggleButton();
</script>



{{-- Styling --}}
<style>
.card {
    background: rgb(255, 255, 255) !important;
}

.avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff8a00, #ff5e00);
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-action {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    margin-left: 25px;
    justify-content: center;
    font-size: 15px;
    background: #f8f9fa;
    border: 1px solid #eee;
    transition: 0.25s ease;
}

.btn-action:hover {
    background: #ffe8d2;
    border-color: var(--orange);
    transform: translateY(-2px);
}

table th, table td {
    padding: 14px 18px;
    white-space: nowrap;
}
.user-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    margin: 15px 0 25px;
}

.user-actions select {
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid #ddd;
    font-size: 14px;
    outline: none;
}

.search-box {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 14px;
    overflow: hidden;
    background: white;
}

.search-box input {
    border: none;
    padding: 10px 14px;
    outline: none;
    width: 220px;
    font-size: 14px;
}

.btn-search {
    border: none;
    background: var(--orange);
    color: white;
    padding: 10px 14px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-search:hover {
    opacity: 0.85;
}

.btn-add {
    background: #ff7a00; /* orange */
    color: white;
    padding: 10px 18px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: 0.2s;
}

.btn-add:hover {
    background: #e96c00;
}


.user-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
}

.user-table thead th {
    text-align: left;
    font-size: 13px;
    color: #777;
    padding: 10px;
}

.user-table tbody tr {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.user-table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
}

.user-table tbody tr:hover {
    background: #fff7f0;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap; /* 🔥 ini yang bikin gak turun */
}

.user-name {
    font-size: 15px;
    font-weight: 500;
}

/* =============================== */
/* TABLE MANAGEMEN USER (FIX) */
/* =============================== */

.table {
    border-collapse: collapse !important;
    border-spacing: 0 !important;
    overflow: hidden;
    border-radius: 16px;
}

/* HEADER WARNA ORANGE */
.table thead {
    background: linear-gradient(90deg, #ff8a00, #ff5e00);
}

.table thead th {
    color: white !important;
    font-weight: 800;
    font-size: 13px;
    text-transform: uppercase;
    padding: 16px;
    border-right: 1px solid rgba(249, 4, 4, 0.25);
}

.table thead th:last-child {
    border-right: none;
}

/* BODY TD GARIS PEMISAH */
.table tbody td {
    border-bottom: 1.5px solid #000000;
    border-right: 1.5px solid #000000;
    padding: 16px;
    font-size: 14px;
}

.table tbody td:last-child {
    border-right: none;
}

/* ZEBRA ROW */
.table tbody tr:nth-child(even) {
    background: rgba(255, 140, 0, 0.04);
}

/* HOVER */
.table tbody tr:hover {
    background: rgba(255, 140, 0, 0.12) !important;
    transition: 0.2s;
}

.card,
.table-responsive {
    padding: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
}
</style>
@endsection
