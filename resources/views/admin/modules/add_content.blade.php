@extends('layouts.app')

@section('content')
<br><br><br> {{-- Jarak sakti lo --}}

<div class="content-body px-lg-5">
    <div class="max-w-900 mx-auto">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-800 text-dark m-0">Kelola Isi Modul</h3>
                <p class="text-muted small">Modul: <span class="text-orange fw-bold">{{ $module->title }}</span></p>
            </div>
        </div>

        <br>

        {{-- Form Tambah Konten --}}
        <div class="main-form-card mb-5">
            <form action="{{ route('admin.modules.storeContent', $module->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h6 class="section-title">TAMBAH SUB-MATERI BARU</h6>

                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Judul Materi</label>
                    <div class="col-sm-9">
                        <input type="text" name="title" class="input-modern" placeholder="Contoh: Pengenalan Routing" required>
                    </div>
                </div>

                <div class="row mb-4 align-items-start">
                    <label class="col-sm-3 label-modern pt-2">Penjelasan Teks</label>
                    <div class="col-sm-9">
                        <textarea name="content" class="input-modern" rows="4" placeholder="Tuliskan rangkuman atau teks materi di sini..."></textarea>
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">Video URL</label>
                    <div class="col-sm-9">
                        <input type="url" name="video_url" class="input-modern" placeholder="https://youtube.com/embed/...">
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <label class="col-sm-3 label-modern">File PDF</label>
                    <div class="col-sm-9">
                        <input type="file" name="file_path" class="input-modern">
                        <small class="text-muted mt-1 d-block">Maksimal 20MB, format PDF.</small>
                    </div>
                </div>

                <div class="form-footer mt-2">
                    <button type="submit" class="btn-save-modern">Tambahkan ke Modul</button>
                    <a href="{{ route('admin.modules.index') }}" class="btn-cancel-modern">Kembali</a>
                </div>
            </form>
        </div>

        <br>

        {{-- Container List Materi --}}
        <div class="mt-5 p-4 p-lg-5 rounded-5" style="background: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 50px;">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2">
                <h6 class="fw-800 text-dark m-0 d-flex align-items-center">
                    <i class="fa-solid fa-layer-group text-orange me-3 fs-2"></i>STRUKTUR MATERI SAAT INI
                </h6>
                <div class="badge bg-white text-orange border px-3 py-2 rounded-pill shadow-sm fw-bold" style="font-size: 25px;">
                    {{ $module->contents->count() }} SUB-MATERI
                </div>
            </div>

            <div class="timeline-wrapper">
                @forelse($module->contents as $index => $content)
                    <div class="content-row mb-3 p-4 bg-white rounded-4 shadow-sm border-0 position-relative overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between w-100 position-relative" style="z-index: 2;">
                            <div class="d-flex align-items-center gap-4">
                                <div class="hover-number text-orange fw-900">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h6 class="fw-800 text-dark mb-1" style="font-size: 16px;">{{ $content->title }}</h6>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        @if($content->video_url)
                                            <span class="badge-modern bg-soft-blue text-primary">VIDEO</span>
                                        @endif
                                        @if($content->file_path)
                                            <span class="badge-modern bg-soft-red text-danger">PDF</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="action-buttons d-flex gap-2">
                                <button class="btn-icon-action bg-light text-muted" title="Edit"></button>
                                <form action="#" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-action bg-soft-red text-danger" title="Hapus"></button>
                                </form>
                            </div>
                        </div>
                        <div class="watermark-number">{{ $index + 1 }}</div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 border border-dashed">
                        <p class="text-muted m-0 fw-bold small">Belum ada materi. Tambahkan materi pertama Anda!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{--
            FOOTER FIX: Spacer ini wajib ada di paling bawah @section('content')
            biar footer RPLearn dipaksa stay di bawah dan gak ganggu watermark jumbo lo.

        --}}
        <div style="height: 150px; clear: both;"></div>

    </div>
</div>
    .fw-800 { font-weight: 800; }
    .fw-900 { font-weight: 900; }
    .text-orange { color: #f37021 !important; }

    .hover-number {
        font-size: 36px;
        width: 45px;
        line-height: 1;
        opacity: 0;
        transform: translateX(-15px);
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .content-row:hover .hover-number {
        opacity: 1;
        transform: translateX(0);
    }

    .content-row {
        transition: 0.3s;
        border: 1px solid transparent !important;
        cursor: pointer;
    }

    .content-row:hover {
        border-color: #f37021 !important;
        background: #d5edfa !important;
        box-shadow: 0 10px 20px rgba(243, 112, 33, 0.05) !important;
    }

    .badge-modern {
        font-size: 9px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        background: #f1f5f9;
        letter-spacing: 0.5px;
    }

    .watermark-number {
        position: absolute;
        right: 30px;
        bottom: 10px;
        font-size: 110px;
        font-weight: 900;
        color: #f1f5f9;
        z-index: 1;
        line-height: 0.8;
        opacity: 0.5;
        user-select: none;
        transition: 0.3s;
    }

    .content-row:hover .watermark-number {
        color: #fff5ed;
        opacity: 0.8;
        transform: scale(1.1);
    }

    .content-body {
        min-height: 90vh;
        padding-bottom: 50px;
    }
@endsection
