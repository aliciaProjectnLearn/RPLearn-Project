@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 30px;"></div>

<div class="content-body px-4 py-4">
    <div class="max-w-900 mx-auto">

        {{-- Tombol Kembali --}}
        <a href="{{ url()->previous() }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-muted mb-4 fw-bold">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

        <div class="bg-white rounded-3 shadow-sm p-4 p-md-5">

            {{-- ✅ Label Kelas --}}
            @if($module->kelas)
                <div class="mb-3">
                    <span class="px-3 py-1 rounded-pill small fw-bold" style="font-size: 12px; background-color: #fff3e0; color: var(--orange, #f57c00); border: 1px solid #ffcc80;">
                        <i class="fa-solid fa-users" style="font-size: 11px;"></i>
                        {{ $module->kelas->nama }}
                    </span>
                </div>
            @endif

            {{-- Header Modul --}}
            <h1 class="fw-bold text-dark mb-2" style="font-size: 1.8rem;">{{ $module->title }}</h1>

            <div class="d-flex flex-wrap align-items-center gap-3 mb-4 text-muted small">
                <span><i class="fa-solid fa-chalkboard-user me-1"></i>{{ $module->teacher->username ?? 'Guru' }}</span>
                <span>|</span>
                <span><i class="fa-solid fa-book me-1"></i>{{ $module->subjectCategory->subject ?? '-' }}</span>
                <span>|</span>
                <span class="badge bg-secondary">Track: {{ $module->track ?? '-' }}</span>
                {{-- ✅ Indikator sudah dibuka --}}
                <span class="badge bg-success ms-auto">
                    <i class="fa-solid fa-circle-check me-1"></i> Sudah Dibuka
                </span>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4 p-3 rounded-3" style="background: #f8fafc; border-left: 4px solid var(--orange, #f57c00);">
                <h6 class="fw-bold text-muted text-uppercase small mb-2">Deskripsi Modul</h6>
                <p class="text-dark mb-0">{{ $module->desc }}</p>
            </div>

            <hr class="my-4">

            {{-- Konten Materi --}}
            <h5 class="fw-bold mb-4">
                <i class="fa-solid fa-layer-group text-primary me-2"></i>
                Materi Pembelajaran
            </h5>

            <div class="d-flex flex-column gap-3">
                @forelse($module->contents as $index => $content)
                    <div class="border rounded-3 p-4" style="border-color: #e2e8f0 !important;">
                        <h6 class="fw-bold mb-3 text-dark">
                            <span class="badge me-2" style="background: var(--orange, #f57c00);">{{ $index + 1 }}</span>
                            {{ $content->title }}
                        </h6>

                        {{-- Video --}}
                        @if($content->video_url)
                            <div class="mb-3 ratio ratio-16x9 rounded-3 overflow-hidden">
                                <iframe src="{{ $content->video_url }}" allowfullscreen></iframe>
                            </div>
                        @endif

                        {{-- Konten Teks --}}
                        @if($content->content)
                            <div class="prose">
                                {!! $content->content !!}
                            </div>
                        @endif

                        {{-- File PDF --}}
                        @if($content->file_path)
                            <a href="{{ asset('storage/' . $content->file_path) }}" target="_blank"
                                class="btn btn-sm btn-outline-danger mt-3">
                                <i class="fa-solid fa-file-pdf me-1"></i> Download PDF Materi
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                        Belum ada konten materi untuk modul ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
