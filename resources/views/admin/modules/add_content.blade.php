@extends('layouts.app')

@section('content')

<div class="module-content-page content-body px-lg-5 py-5">
    <div class="container-fluid" style="max-width: 1100px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-900 text-dark m-0">Kelola Isi Modul</h3>
                <p class="text-muted small">
                    Modul: <span class="text-orange fw-bold">{{ $module->title }}</span>
                </p>
            </div>
        </div>


        {{-- ============================= --}}
        {{-- LIST SUBMATERI (NAIK KE ATAS) --}}
        {{-- ============================= --}}
        <div class="materi-list-box mb-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-900 m-0">
                    <i class="fa-solid fa-layer-group text-orange me-2"></i>
                    Struktur Materi Saat Ini
                </h5>

                <br>

                <span class="badge-count">
                    {{ $module->contents->count() }} Sub-Materi
                </span>

                <br>
                <br>
            </div>

            @forelse($module->contents as $index => $content)
                <div class="content-row">

                    <div class="left-info">
                        <div class="hover-number">{{ $index + 1 }}</div>

                        <div>
                            <h6 class="fw-800 mb-1">{{ $content->title }}</h6>

                            <div class="d-flex gap-2">
                                @if($content->video_url)
                                    <span class="badge-modern bg-soft-blue">VIDEO</span>
                                @endif

                                @if($content->file_path)
                                    <span class="badge-modern bg-soft-red">PDF</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="right-action">
                        <a href="{{ route('admin.modules.content.show', $content->id) }}"
                            class="btn-icon-action">
                            👁
                        </a>
                    </div>


                </div>

            @empty
                <div class="empty-box">
                    Belum ada sub-materi. Tambahkan yang pertama!
                </div>
            @endforelse
        </div>

        <br>



        {{-- ============================= --}}
        {{-- FORM TAMBAH (PINDAH KE BAWAH) --}}
        {{-- ============================= --}}
        <div class="main-form-card">

            <h4 class="fw-900 mb-4">
                <i class="fa-solid fa-plus text-orange me-2"></i>
                Tambah Sub-Materi Baru
            </h4>

            <br>

            <form action="{{ route('admin.modules.storeContent', $module->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="label-modern">Judul Materi</label>
                    <input type="text" name="title" class="input-modern" placeholder="Contoh: Pengenalan Routing" required>
                </div>

                <br>

                <div class="mb-3">
                    <label class="label-modern">Penjelasan</label>
                    <textarea name="content" rows="4" class="input-modern" placeholder="Tuliskan isi materi di sini..."></textarea>
                </div>

                <br>

                <div class="mb-3">
                    <label class="label-modern">Video URL</label>
                    <input type="url" name="video_url" class="input-modern" placeholder="https://www.youtube.com/embed...">
                </div>

                <br>

                <div class="mb-3">
                    <label class="label-modern">File PDF</label>
                    <input type="file" name="file_path" class="input-modern">
                    <small class="text-muted">Max 20MB, PDF only</small>
                </div>

                <div class="form-footer mt-4">
                    <button type="submit" class="btn-save-modern">
                        Tambahkan Materi
                    </button>

                    <a href="{{ route('admin.modules.index') }}" class="btn-cancel-modern">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>


{{-- ============================= --}}
{{-- STYLE KHUSUS PAGE INI (AMAN) --}}
{{-- ============================= --}}
<style>
.module-content-page {
    font-family: "Inter", sans-serif;
}

.fw-800 {
    font-weight: 800;
    font-size: 17px;
}
.fw-900 { font-weight: 900; }

.text-orange {
    color: #f37021 !important;
}

.main-form-card {
    background: white;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
}

.input-modern {
    width: 100%;
    padding: 12px 15px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    outline: none;
}

.input-modern:focus {
    border-color: #f37021;
}

.label-modern {
    font-weight: 700;
    margin-bottom: 6px;
    display: block;
}

.btn-save-modern {
    background: #f37021;
    color: white;
    padding: 10px 22px;
    border-radius: 12px;
    border: none;
    font-weight: 800;
}

.btn-cancel-modern {
    margin-left: 10px;
    padding: 10px 20px;
    border-radius: 12px;
    background: #f1f5f9;
    font-weight: 700;
    text-decoration: none;
    color: black;
}

.materi-list-box {
    background: #f8fafc;
    padding: 30px;
    border-radius: 18px;
}

.badge-count {
    background: white;
    padding: 8px 18px;
    border-radius: 999px;
    font-weight: 800;
    border: 1px solid #eee;
}

.content-row {
    background: white;
    padding: 18px 20px;
    border-radius: 16px;
    margin-bottom: 15px;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.25s;
}

.content-row:hover {
    border: 1px solid #f37021;
    transform: translateY(-3px);
}

.left-info {
    display: flex;
    gap: 15px;
    align-items: center;
}

.hover-number {
    font-size: 26px;
    font-weight: 900;
    color: #f37021;
}

.badge-modern {
    font-size: 10px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 8px;
}

.bg-soft-blue {
    background: #e0f2fe;
    color: #0284c7;
}

.bg-soft-red {
    background: #fee2e2;
    color: #dc2626;
}

.right-action {
    display: flex;
    gap: 10px;
}

.btn-icon-action {
    border: none;
    background: #f1f5f9;
    padding: 8px 10px;
    border-radius: 10px;
    cursor: pointer;
}

.btn-icon-action.danger {
    background: #fee2e2;
    color: red;
}

.watermark-number {
    position: absolute;
    right: 20px;
    bottom: 0;
    font-size: 70px;
    opacity: 0.07;
    font-weight: 900;
}

.empty-box {
    padding: 40px;
    text-align: center;
    border: 2px dashed #ddd;
    border-radius: 16px;
}
</style>

@endsection
