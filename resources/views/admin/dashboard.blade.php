@extends('layouts.app')

@section('content')

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif

<div class="container-fluid py-4 admin-dashboard">

    {{-- HERO --}}
    <div class="admin-hero mb-4">
        <h1 class="m-0">Selamat Datang, <span>Admin!</span></h1>
        <p class="mb-0 opacity-75">Ringkasan statistik sistem RPLearn hari ini.</p>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-4 mb-4">
        @php
            $cards = [
                ['label' => 'Total Modul', 'value' => $summary['total_modul'], 'color' => 'warning'],
                ['label' => 'Total Pengguna', 'value' => $summary['total_user'], 'color' => 'info'],
                ['label' => 'Istilah Kamus', 'value' => $summary['total_kamus'], 'color' => 'secondary'],
                ['label' => 'Total Siswa', 'value' => $summary['total_siswa'], 'color' => 'primary'],
                ['label' => 'Total Guru', 'value' => $summary['total_guru'], 'color' => 'success'],
                ['label' => 'Total Admin', 'value' => $summary['total_admin'], 'color' => 'danger'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-lg-4 col-md-6">
            <div class="card admin-card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">{{ $card['label'] }}</p>
                    <h3 class="fw-bold text-{{ $card['color'] }}">
                        {{ $card['value'] }}
                    </h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHART + STATS --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="card admin-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Distribusi Role</h5>
                    <div style="height:300px;">
                        <canvas id="roleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card admin-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Statistik Singkat</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent border-0 px-0">
                            Total Modul: <strong>{{ $summary['total_modul'] }}</strong>
                        </li>
                        <li class="list-group-item bg-transparent border-0 px-0">
                            Total Kamus: <strong>{{ $summary['total_kamus'] }}</strong>
                        </li>
                        <li class="list-group-item bg-transparent border-0 px-0">
                            Total User: <strong>{{ $summary['total_user'] }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card admin-card">
        <div class="card-body">
            <h5 class="fw-bold mb-4">User Terbaru</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tanggal Join</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary['user_terbaru'] as $u)
                        <tr>
                            <td>{{ $u->username }}</td>
                            <td>
                                <span class="badge
                                    @if($u->role == 'admin') bg-danger
                                    @elseif($u->role == 'guru') bg-success
                                    @else bg-primary
                                    @endif">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td>{{ $u->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada user
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


{{-- STYLE KHUSUS DASHBOARD --}}
<style>
.admin-dashboard {
    background-color: #f5f7fa;
    min-height: 100vh;
}

.admin-hero {
    background: #F6973F;
    padding: 30px;
    border-radius: 20px;
    color: white;
}

.admin-hero span {
    font-weight: 900;
}

.admin-card {
    background: white;
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}
</style>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('roleChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Siswa', 'Guru', 'Admin'],
            datasets: [{
                data: [
                    {{ $summary['total_siswa'] }},
                    {{ $summary['total_guru'] }},
                    {{ $summary['total_admin'] }}
                ],
                backgroundColor: ['#3b82f6','#10b981','#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        padding: 20
                    }
                }
            }
        }
    });

});
</script>
@endpush

@endsection
