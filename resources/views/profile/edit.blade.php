@extends('layouts.app')

@section('content')

{{-- WRAPPER UTAMA --}}
<div class="profile-page-container">

    {{-- ALERT STATUS (Opsional) --}}
    @if (session('status') === 'profile-updated')
        <div class="alert-success">
            ✓ Profil berhasil diperbarui!
        </div>
    @endif

    {{-- LAYOUT GRID (Kiri & Kanan) --}}
    <div class="profile-grid">

        {{-- BAGIAN KIRI: SIDEBAR PROFILE --}}
        <aside class="profile-sidebar">
            <div class="custom-card profile-card">
                {{-- Header Warna Orange --}}
                <div class="profile-header-bg">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($user->username, 0, 2)) }}
                    </div>
                    <h2 class="profile-name">{{ $user->username }}</h2>
                    <p class="profile-email">{{ $user->email ?? 'admin@rplearn.com' }}</p>
                </div>

                {{-- Menu Navigasi --}}
                <div class="profile-menu">
                    <div class="menu-label">MENU</div>
                    <a href="#" class="menu-item active">
                        <span class="icon">👤</span> Edit Profile
                    </a>
                    <a href="#security-section" class="menu-item">
                        <span class="icon">🔒</span> Keamanan
                    </a>

                    {{-- Logout Form --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="menu-item logout-btn">
                            <span class="icon">🚪</span> Logout
                        </button>
                    </form>
                </div>

                <div class="profile-footer">
                    Bergabung sejak {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                </div>
            </div>
        </aside>

        {{-- BAGIAN KANAN: FORM CONTENT --}}
        <main class="profile-content">

            {{-- Banner Info --}}
            <div class="info-banner">
                <div class="banner-text">
                    <strong>Halo, {{ $user->username }}!</strong>
                    <p>Pastikan data profil Anda selalu update untuk keamanan.</p>
                </div>
            </div>

            {{-- Form Edit --}}
            <div class="custom-card">
                <div class="card-title">
                    <h4>Edit Informasi</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        {{-- Input Group: Username --}}
                        <div class="form-group">
                            <label>USERNAME</label>
                            <input type="text" name="username" class="custom-input" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-action">
                            <button type="submit" class="btn-save">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security Section --}}
            <div id="security-section" class="custom-card" style="margin-top: 30px;">
                <div class="card-title">
                    <h4>Keamanan Sistem</h4>
                </div>
                <div class="card-body security-flex">
                    <div class="security-text">
                        <strong>Password Sistem</strong>
                        <p>Gunakan kombinasi password yang kuat.</p>
                    </div>
                    <a href="#" class="btn-outline">Ganti Password</a>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- CSS KHUSUS HALAMAN INI (Custom CSS) --}}
<style>
    html {
        scroll-behavior: smooth;
    }
    /* 1. RESET & LAYOUT UTAMA */
    .profile-page-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    .page-header { margin-bottom: 30px; }
    .page-header h3 { font-size: 24px; font-weight: 800; margin: 0 0 5px 0; color: #2d3748; }
    .page-header p { margin: 0; color: #718096; font-size: 14px; }

    /* GRID SYSTEM (Kunci agar tidak berantakan) */
    .profile-grid {
        display: grid;
        grid-template-columns: 350px 1fr; /* Kiri fix 350px, Kanan sisa layar */
        gap: 30px;
        align-items: start;
    }

    /* Responsive untuk HP (Stack ke bawah) */
    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
    }

    /* 2. STYLE KARTU (CARD) */
    .custom-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden; /* Biar header gak keluar radius */
        border: 1px solid #f0f0f0;
    }

    .profile-header-bg {
        background: #f37021; /* ORANYE RPLEARN */
        padding: 25px 5px;
        text-align: center;
        color: white;
    }

    .avatar-circle {
        width: 90px;
        height: 90px;
        background: white;
        color: #f37021;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 800;
        margin: 0 auto 15px auto;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .profile-name { font-size: 20px; font-weight: 700; margin: 0; }
    .profile-email { font-size: 13px; opacity: 0.8; margin-top: 5px; }

    .profile-menu { padding: 20px 0; }
    .menu-label { font-size: 11px; font-weight: bold; color: #a0aec0; padding: 0 25px 10px; letter-spacing: 1px; }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 25px;
        text-decoration: none;
        color: #4a5568;
        font-weight: 600;
        font-size: 14px;
        transition: 0.2s;
        border-left: 4px solid transparent;
    }

    .menu-item:hover { background: #fff5ed; color: #f37021; }
    .menu-item.active { background: #fff5ed; color: #f37021; border-left-color: #f37021; }
    .menu-item .icon { margin-right: 15px; width: 20px; text-align: center; }

    .logout-btn {
        background: none; border: none; width: 100%; cursor: pointer; color: #e53e3e;
        text-align: left; font-family: inherit;
    }
    .logout-btn:hover { background: #fff5f5; color: #c53030; }

    .profile-footer {
        text-align: center; padding: 15px; background: #f9fafb;
        font-size: 12px; color: #718096; border-top: 1px solid #edf2f7;
    }

    /* 4. CONTENT KANAN */
    .info-banner {
        background: linear-gradient(135deg, #f37021 0%, #ff9f43 100%);
        color: white;
        padding: 13px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .banner-text p { margin: 0; font-size: 13px; opacity: 0.9; }

    .card-title {
        padding: 20px 30px;
        border-bottom: 1px solid #edf2f7;
    }
    .card-title h4 { margin: 0; font-size: 16px; font-weight: 700; color: #2d3748; }

    .card-body { padding: 13px; }

    /* 5. FORM INPUT */
    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; font-size: 12px; font-weight: 700; color: #718096; margin-bottom: 8px; letter-spacing: 0.5px; }

    .custom-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box; /* PENTING: Biar padding gak bikin input melebar */
    }
    .custom-input:focus { border-color: #f37021; box-shadow: 0 0 0 3px rgba(243, 112, 33, 0.1); }
    .custom-input.disabled { background: #f7fafc; color: #a0aec0; cursor: not-allowed; }

    .helper-text { font-size: 12px; color: #a0aec0; font-style: italic; margin-top: 5px; display: block; }
    .error-msg { color: #e53e3e; font-size: 12px; margin-top: 5px; display: block; font-weight: bold; }

    .form-action { text-align: right; margin-top: 10px; }

    /* 6. BUTTONS */
    .btn-save {
        background: #f37021;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-save:hover { background: #d95d16; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(243, 112, 33, 0.2); }

    .btn-outline {
        text-decoration: none;
        color: #4a5568;
        border: 1px solid #cbd5e0;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s;
    }
    .btn-outline:hover { background: #f7fafc; color: #2d3748; border-color: #a0aec0; }

    /* 7. KEAMANAN SECTION */
    .security-flex { display: flex; justify-content: space-between; align-items: center; }
    .security-text strong { display: block; font-size: 15px; margin-bottom: 4px; }
    .security-text p { margin: 0; font-size: 13px; color: #718096; }

    /* 8. ALERT */
    .alert-success {
        background: #f0fff4; color: #2f855a; border: 1px solid #c6f6d5;
        padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;
    }
</style>
@endsection
