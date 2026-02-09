@extends('layouts.app')

@section('content')

    {{-- 🔔 LOGIN SUCCESS ALERT --}}
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true,
            background: '#393E46',
            color: '#ffffff',
            backdrop: `
                rgba(0,0,0,0.4)
                url("{{ asset('images/nyan-cat.gif') }}")
                left top
                no-repeat
            `
        });
    </script>
    @endif

<div class="admin-spacer"></div>

<div class="page-content">

    {{-- HERO --}}
    <div class="hero" style="margin-top: 0; padding: 20px 0;">
        <h1>Selamat Datang, <span>Admin!</span></h1>
        <p>Ringkasan statistik sistem RPLearn hari ini.</p>
    </div>

    {{-- STAT GRID --}}
    <div class="admin-grid">

        <div class="admin-stat-card">
            <span class="stat-label">Total Modul</span>
            <span class="stat-value text-orange">
                {{ $summary['total_modul'] }}
            </span>
        </div>

        <div class="admin-stat-card">
            <span class="stat-label">Total Pengguna</span>
            <span class="stat-value text-orange">
                {{ $summary['total_user'] }}
            </span>
        </div>

        <div class="admin-stat-card">
            <span class="stat-label">Istilah Kamus</span>
            <span class="stat-value text-orange">
                {{ $summary['total_kamus'] }}
            </span>
        </div>

        {{-- ROLE STAT --}}
        <div class="admin-stat-card">
            <span class="stat-label">Total Siswa</span>
            <span class="stat-value text-orange">
                {{ $summary['total_siswa'] }}
            </span>
        </div>

        <div class="admin-stat-card">
            <span class="stat-label">Total Guru</span>
            <span class="stat-value text-orange">
                {{ $summary['total_guru'] }}
            </span>
        </div>

        <div class="admin-stat-card">
            <span class="stat-label">Total Admin</span>
            <span class="stat-value text-orange">
                {{ $summary['total_admin'] }}
            </span>
        </div>

    </div>

    <br>

    {{-- USER TERBARU --}}
    <div class="admin-card-box mt-5">
        <div class="admin-card-header">
            <h5>User Terbaru Bergabung</h5>
            <p>5 akun terakhir yang baru terdaftar</p>
        </div>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Tanggal Join</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['user_terbaru'] as $u)
                    <tr>
                        <td>{{ $u->username }}</td>
                        <td>
                            <span class="role-badge role-{{ $u->role }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>{{ $u->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($summary['user_terbaru']->isEmpty())
<tr>
    <td colspan="3" style="text-align:center; padding:20px;">
        Belum ada user baru 😅
    </td>
</tr>
@endif

</div>

<style>
/* =============================== */
/* USER TERBARU TABLE (ADMIN) */
/* =============================== */

.admin-card-header h5 {
    font-weight: 800;
    font-size: 1.2rem;
    margin-bottom: 3px;
}

.admin-card-header p {
    margin: 0;
    font-size: 0.9rem;
    color: #7f8c8d;
}

.admin-table-wrapper {
    margin-top: 15px;
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.admin-table thead {
    background: #f8f9fb;
}

.admin-table th {
    text-align: left;
    padding: 14px;
    font-weight: 700;
    color: #2c3e50;
    border-bottom: 2px solid #eee;
}

.admin-table td {
    padding: 14px;
    border-bottom: 1px solid #eee;
    color: #444;
}

.admin-table tr:hover {
    background: rgba(255, 140, 0, 0.05);
}

/* Role Badge */
.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
}

/* Role Colors */
.role-siswa {
    background: rgba(52, 152, 219, 0.15);
    color: #3498db;
}

.role-guru {
    background: rgba(46, 204, 113, 0.15);
    color: #2ecc71;
}

.role-admin {
    background: rgba(255, 140, 0, 0.15);
    color: var(--orange);
}

</style>
@endsection
