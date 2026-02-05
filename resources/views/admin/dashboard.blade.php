@extends('layouts.app')

@section('content')
    <div class="admin-spacer"></div>
    <div class="page-content">
        <div class="hero" style="margin-top: 0; padding: 20px 0;">
            <h1>Selamat Datang, <span>Admin!</span></h1>
            <p>Ringkasan statistik sistem RPLearn hari ini.</p>
        </div>

        <div class="admin-grid">
            <div class="admin-stat-card">
                <span class="stat-label">Total Modul</span>
                {{-- Ambil data asli dari database lewat controller --}}
                <span class="stat-value text-orange">{{ $summary['total_modul'] }}</span>
            </div>
            <div class="admin-stat-card">
                <span class="stat-label">Total Pengguna</span>
                {{-- Dinamis sesuai jumlah user yang terdaftar --}}
                <span class="stat-value text-orange">{{ $summary['total_user'] }}</span>
            </div>
            <div class="admin-stat-card">
                <span class="stat-label">Istilah Kamus</span>
                {{-- Dinamis sesuai jumlah kamus yang terdaftar --}}
                <span class="stat-value text-orange">{{ $summary['total_kamus'] }}</span>
            </div>
        </div>
    </div>
@endsection
