@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 90px;"></div>

<div class="content-body px-4 pb-5">
    <div class="max-w-900 mx-auto">

        {{-- Header Profil: Kasih mb-5 biar ada jarak ke bawah --}}
        <div class="page-header mb-5 pb-3 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-800 text-dark m-0">Pengaturan Profil</h3>
                <p class="text-muted small m-0">Kelola informasi keamanan dan akun <span class="text-orange fw-bold">RPLearn</span> Anda</p>
            </div>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-5 py-3 px-4 animate__animated animate__fadeIn">
                <i class="fa-solid fa-circle-check me-2 text-success"></i> Profil admin berhasil diperbarui!
            </div>
        @endif

        {{-- Section 1: Informasi Akun (Padding p-5 biar lega) --}}
        <div class="main-form-card shadow-sm border-0 bg-white rounded-4 p-5 mb-5">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-indicator me-3" style="width: 10px; height: 30px; border-radius: 5px;"></div>
                <h6 class="fw-800 text-dark m-0" style="letter-spacing: 1px;">INFORMASI AKUN</h6>
            </div>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="mb-5"> {{-- Jarak input ke tombol simpan --}}
                    <label class="fw-bold text-secondary small d-block mb-3" style="letter-spacing: 1px;">USERNAME</label>
                    <br>
                    <br>
                    <input type="text" name="username" class="input-modern-premium w-100" value="{{ old('username', $user->username) }}" required style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px 20px;">
                    @error('username')
                        <span class="text-danger small mt-2 d-block fw-bold">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div class="pt-4 border-top d-flex justify-content-end">
                    <button type="submit" class="btn-save-modern border-0 text-white fw-bold px-5 py-3 rounded-3"
                            style="background: #f37021; transition: 0.3s;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <br>

        {{-- Section 2: Keamanan (Jarak antar kartu mb-5) --}}
    <div class="main-form-card shadow-sm border-0 bg-white rounded-4 p-5">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-indicator bg-dark me-3" style="width: 4px; height: 24px; border-radius: 10px;"></div>
        <h6 class="fw-800 text-dark m-0" style="letter-spacing: 1px;">KEAMANAN & AKSES</h6>
    </div>

    {{-- Wrapper tanpa ikon, pake border-left oranye buat aksen --}}
    <div class="security-wrapper p-4 rounded-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
            {{-- Teks Deskripsi --}}
            <div class="flex-grow-1">
                <p class="text-dark fw-800 mb-2" style="font-size: 16px;">Kata Sandi Sistem</p>
                <p class="text-muted small m-0" style="max-width: 550px; line-height: 1.7;">
                    Demi keamanan akun <span class="text-orange fw-bold">RPLearn</span>, pastikan anda gunain kombinasi password yang kuat. Jangan kasih tau username atau password anda ke siapa pun.
                </p>
            </div>

            <br>

            {{-- Tombol Action --}}
            <div class="ms-md-auto">
                <a href="#" class="btn-security-action text-decoration-none">
                    <i class="fa-solid fa-key me-2"></i> Ganti Password
                </a>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<style>
    .bg-soft-orange { background: #fff5ed; }
    .text-orange { color: #f37021 !important; }
    .input-modern-premium:focus { border-color: #f37021 !important; box-shadow: 0 0 0 4px rgba(243, 112, 33, 0.1); }
    .btn-save-modern:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(243, 112, 33, 0.2); }
    .btn-link-premium:hover { background: #f37021 !important; color: white !important; }
   .btn-security-action {
        background: #f37021;
        color: #ffffff !important;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(243, 112, 33, 0.2);
        display: inline-block;
        border: none;
    }

    .btn-security-action:hover {
        background: #d95d16;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(243, 112, 33, 0.3);
    }

    /* ============================= */
/* FIX FONT PROFIL BIAR GAK MINI */
/* ============================= */

/* Header utama */
.page-header h3 {
    font-size: 28px !important;
    font-weight: 800 !important;
}

.page-header p {
    font-size: 15px !important;
}

/* Judul Section */
.main-form-card h6 {
    font-size: 18px !important;
    font-weight: 800 !important;
}

/* Label Username */
label {
    font-size: 14px !important;
    font-weight: 700 !important;
}

/* Input Username */
.input-modern-premium {
    font-size: 16px !important;
    padding: 16px 20px !important;
}

/* Tombol Simpan */
.btn-save-modern {
    font-size: 15px !important;
    padding: 14px 40px !important;
    border-radius: 14px !important;
}

/* Bagian Keamanan */
.security-wrapper p:first-child {
    font-size: 17px !important;
    font-weight: 800 !important;
}

/* Deskripsi keamanan jangan kecil banget */
.security-wrapper p.text-muted {
    font-size: 16px !important;
    line-height: 1.8 !important;
}

/* Tombol Ganti Password lebih gede */
.btn-security-action {
    font-size: 15px !important;
    padding: 15px 35px !important;
    border-radius: 14px !important;
}

</style>
@endsection
