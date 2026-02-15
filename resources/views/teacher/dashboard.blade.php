@extends('layouts.app')

@section('content')
    <div class="admin-spacer"></div>

    <div class="page-content">

        {{-- HERO --}}
        <div class="hero" style="margin-top: 0; padding: 20px 0;">
            <h1>Selamat Datang, <span>{{ auth()->user()->username }}!</span></h1>
            <p>Kelola modul dan pantau aktivitas belajar siswa Anda.</p>
        </div>

        {{-- STAT GRID --}}
        <div class="admin-grid">

            <div class="admin-stat-card">
                <span class="stat-label">Total Modul Anda</span>
                <span class="stat-value text-orange">
                    {{ $summary['total_modul'] }}
                </span>
            </div>

            <div class="admin-stat-card">
                <span class="stat-label">Total Pertanyaan</span>
                <span class="stat-value text-orange">
                    {{ $summary['total_pertanyaan'] }}
                </span>
            </div>

            <div class="admin-stat-card">
                <span class="stat-label">Total Jawaban</span>
                <span class="stat-value text-orange">
                    {{ $summary['total_jawaban'] }}
                </span>
            </div>

            <div class="admin-stat-card">
                <span class="stat-label">Total Siswa</span>
                <span class="stat-value text-orange">
                    {{ $summary['total_siswa'] }}
                </span>
            </div>

        </div>

        <br>

        {{-- MODUL TERBARU --}}
        {{-- <div class="card-box mt-4">
            <div class="card-header">
                <h5>📚 Modul Terbaru Anda</h5>
                <p>5 modul terakhir yang Anda upload</p>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Judul Modul</th>
                            <th>Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary['modul_terbaru'] as $modul)
                            <tr>
                                <td>{{ $modul->title }}</td>
                                <td>{{ $modul->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center empty-row">
                                    Belum ada modul 😅
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> --}}

        <div class="card-box shadow-sm border-0 mt-4">
            <div class="card-body">
                <h5 class="mb-3 fw-bold" style="color:#222831;">
                    Statistik Upload Modul ({{ now()->year }})
                </h5>
                <canvas id="moduleChart" height="100"></canvas>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('moduleChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(214, 165, 49, 0.5)');
        gradient.addColorStop(1, 'rgba(214, 165, 49, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Upload Modul',
                    data: @json($totals),
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#d6a531',
                    tension: 0.4, // smooth curve
                    pointBackgroundColor: '#222831',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...@json($totals)) + 5,
                        ticks: {
                            color: '#393e46'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#393e46'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
