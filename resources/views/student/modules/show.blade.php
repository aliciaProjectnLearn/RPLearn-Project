@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 30px;"></div>

<div class="content-body px-4 py-4">

    <div class="rounded-3 shadow-sm p-4 p-md-5" style="background: none">
        {{-- Header Modul --}}
        <div class="module-header">
            <a href="{{ url()->previous() }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-muted mb-4 fw-bold">
                <i class="ri-arrow-left-line"></i> Kembali
            </a>
            <button id="saveBtn"
                class="save-btn {{ $module->isSaved ? 'saved ri-bookmark-fill' : 'ri-bookmark-line' }}"
                data-id="{{ $module->id }}">
            </button>
        </div>

        {{-- Label Kelas --}}
        @if($module->kelas)
            <div class="mb-3">
                <span class="px-3 py-1 rounded-pill small fw-bold" style="font-size: 12px; background-color: #fff3e0; color: var(--orange, #f57c00); border: 1px solid #ffcc80;">
                    <i class="ri-users-line" style="font-size: 11px;"></i>
                    {{ $module->kelas->nama }}
                </span>
            </div>
        @endif

        <h1 class="fw-bold text-dark mb-0">{{ $module->title }}</h1>

        <div class="d-flex flex-wrap align-items-center gap-3 mb-4 text-muted small">
            <span><i class="ri-chalkboard-user-line me-1"></i>{{ $module->teacher->username ?? 'Guru' }}</span>
            <span>|</span>
            <span><i class="ri-book-line me-1"></i>{{ $module->subjectCategory->subject ?? '-' }}</span>
            <span>|</span>
            <span class="badge bg-secondary">Track: {{ $module->track ?? '-' }}</span>
            <span class="badge bg-success ms-auto">
                <i class="ri-check-line me-1"></i> Sudah Dibuka
            </span>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-4 p-3 rounded-3" style="background: #f8fafc; border-left: 4px solid var(--orange, #f57c00);">
            <h6 class="fw-bold text-muted text-uppercase small mb-2">Deskripsi Modul</h6>
            <p class="text-dark mb-0" style="font-size: 14px">{{ $module->desc }}</p>
        </div>

        <hr class="my-4">

        {{-- Konten Materi --}}
        <h5 class="fw-bold mb-4">
            <i class="ri-file-text-line text-orange me-2"></i>
            Materi Pembelajaran
        </h5>

        <div class="d-flex flex-column gap-3">
            @forelse($module->contents as $index => $content)
                <div class="border rounded-3 p-4" style="border-color: #e2e8f0 !important;">
                    <h6 class="fw-bold mb-3 text-dark">
                        <span class="badge me-2" style="background: var(--orange, #f57c00);">{{ $index + 1 }}</span>
                        {{ $content->title }}
                    </h6>

                    {{-- ✅ Video YouTube: embed dengan parameter agar bisa play --}}
                    @if($content->video_url)
                        @php
                            // Normalisasi URL: pastikan format embed yang benar
                            $videoUrl = $content->video_url;

                            // Jika masih format watch?v=, convert ke embed
                            if (str_contains($videoUrl, 'watch?v=')) {
                                $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                            }

                            // Jika youtu.be short link
                            if (str_contains($videoUrl, 'youtu.be/')) {
                                preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                                $videoId = $matches[1] ?? '';
                                $videoUrl = "https://www.youtube.com/embed/{$videoId}";
                            }

                            // Tambahkan parameter agar tidak diblokir
                            $separator = str_contains($videoUrl, '?') ? '&' : '?';
                            $videoUrl .= $separator . 'rel=0&modestbranding=1';
                        @endphp
                        <div class="mb-3 ratio ratio-16x9 rounded-3 overflow-hidden">
                            <iframe
                                src="{{ $videoUrl }}"
                                title="{{ $content->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"
                            ></iframe>
                        </div>
                    @endif

                    {{-- Konten Teks --}}
                    @if($content->content)
                        <div class="prose">
                            {!! $content->content !!}
                        </div>
                    @endif

                    {{-- ✅ File PDF: download via route agar tidak 404 --}}
                    @if($content->file_path)
                        <a href="{{ route('student.modules.download-pdf', $content->id) }}"
                            class="btn btn-sm btn-outline-danger mt-3">
                            <i class="ri-file-pdf-line me-1"></i> Download PDF Materi
                        </a>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="ri-inbox-line fa-2x mb-2 d-block"></i>
                    Belum ada konten materi untuk modul ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.module-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 10px;
}

h1 {
    font-size: 28px;
}

.save-btn {
    font-size: 20px;
    border: 2px solid #f57c00;
    color: #f57c00;
    background: transparent;
    border-radius: 12px;
    padding: 8px 12px;
    cursor: pointer;
    transition: .2s;
}

.save-btn:hover {
    transform: scale(1.05);
}

.save-btn.saved {
    background: #f57c00;
    color: white;
    border-color: #f57c00;
}

.prose p {
    margin-bottom: 10px;
}

.rounded-3.shadow-sm {
    background: white;
    border: 1px solid #eee;
}
</style>

@push('scripts')
<script>
document.getElementById('saveBtn')?.addEventListener('click', function () {
    const btn = this;
    const moduleId = btn.dataset.id;

    fetch(`/student/modules/${moduleId}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.saved) {
            btn.classList.add('saved', 'ri-bookmark-fill');
            btn.classList.remove('ri-bookmark-line');
        } else {
            btn.classList.remove('saved', 'ri-bookmark-fill');
            btn.classList.add('ri-bookmark-line');
        }
    });
});
</script>
@endpush

@endsection
