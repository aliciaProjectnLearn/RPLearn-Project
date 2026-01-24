@extends('layouts.app')

@section('content')

{{-- Flash success popup --}}
@if (session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

<section class="hero">
    <h1>Start <span>Learning.</span> Keep Growing.</h1>
    <p>RPLearn is designed to support vocational students <br>in developing real-world skills through structured and guided learning.</p>

    <div class="features-card">
        <div class="feat-card"><i class="ri-book-open-line"></i><span>Learning Modules</span></div>
        <div class="feat-card"><i class="ri-bookmark-line"></i><span>Dictionary</span></div>
        <div class="feat-card"><i class="ri-question-line"></i><span>FAQ</span></div>
    </div>
</section>

<section class="module-section" id="module-section">
    <h1>Cari <span>Modul</span> Belajarmu!</h1>

    {{-- SEARCH & FILTER UPGRADED --}}
    <form action="{{ url()->current() }}" method="GET" class="module-filter-form">
        <div class="search-wrapper">
            <div class="module-search">
                <i class="ri-search-line"></i>
                <input type="text" name="search" placeholder="Mau belajar apa hari ini?" value="{{ request('search') }}">
            </div>
        </div>

        <div class="filter-group-modern">
            {{-- Dropdown Kelas --}}
            <div class="custom-select-wrapper">
                <i class="ri-government-line select-icon"></i>
                <select name="grade_id" onchange="this.form.submit()">
                    <option value="" {{ !request('grade_id') ? 'selected' : '' }}>Semua Kelas</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->grade }}
                        </option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line arrow-icon"></i>
            </div>

            {{-- Dropdown Materi --}}
            <div class="custom-select-wrapper">
                <i class="ri-book-3-line select-icon"></i>
                <select name="subject_id" onchange="this.form.submit()">
                    <option value="" {{ !request('subject_id') ? 'selected' : '' }}>Semua Materi</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->subject }}
                        </option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line arrow-icon"></i>
            </div>
        </div>
    </form>

    {{-- MODULE CARDS --}}
    <div class="module-cards">
        @forelse($modules as $module)
            <div class="module-card">
                <div class="card-header-row">
                    {{-- TEKS KELAS | MATERI WARNA ORANYE --}}
                    <span class="module-meta-text">
                        {{ $module->gradeCategory->grade ?? 'Kelas' }} | {{ $module->subjectCategory->subject ?? 'Materi' }}
                    </span>

                    {{-- IKON MEDIA SEJAJAR HORIZONTAL --}}
                    <div class="media-icons-row">
                        @php
                            $hasVideo = $module->contents->whereNotNull('video_url')->count() > 0;
                            $hasPdf = $module->contents->whereNotNull('file_path')->count() > 0;
                        @endphp
                        @if($hasVideo) <i class="ri-youtube-fill" style="color: #FF0000;"></i> @endif
                        @if($hasPdf) <i class="ri-file-pdf-2-fill" style="color: #f15a24;"></i> @endif
                    </div>
                </div>

                <h3 class="module-title" onclick="showDetail({{ $module->id }})">{{ $module->title }}</h3>

                <div class="author-label">
                    <i class="ri-user-3-line"></i> Oleh: <strong>{{ $module->teacher->name ?? 'Admin' }}</strong>
                </div>

                <p class="module-desc-text">{{ Str::limit($module->desc, 80) }}</p>

                {{-- TOMBOL ORANYE DENGAN HOVER --}}
                <button onclick="showDetail({{ $module->id }})" class="btn-pelajari-orange">
                    Pelajari Sekarang <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: #333; padding: 20px;">Modul tidak ditemukan.</p>
        @endforelse
    </div>
</section>

{{-- FAQ SECTION --}}
<section class="faq-section" id="faq-section">
    <h2>F <span>A</span> Q</h2>
    <div class="faq-box">Apa itu RPLearn?</div>
    <div class="faq-box">Bagaimana cara belajar?</div>
</section>

<section class="dictionary-section reveal">
    <h2>Dictionary</h2>
    <p class="dictionary-desc">Learn common technical terms used in vocational learning.</p>
    <div class="dictionary-search">
        <i class="ri-search-line"></i>
        <input type="text" placeholder="Search terms..." />
    </div>
    <div class="dictionary-cards reveal" id="dictionary-cards">
        <div class="dictionary-card">
            <h4>HTML</h4>
            <p>HyperText Markup Language used to structure web content.</p>
        </div>
        <div class="dictionary-card">
            <h4>CSS</h4>
            <p>Cascading Style Sheets used to style and layout web pages.</p>
        </div>
        <div class="dictionary-card">
            <h4>JavaScript</h4>
            <p>A programming language that adds interactivity to websites.</p>
        </div>
    </div>
    <div class="dictionary-more">
        <a href="#">View Full Dictionary</a>
    </div>
</section>

{{-- MODAL DETAIL (SPLIT LAYOUT) --}}
<div id="moduleModal" class="modal-overlay">
    <div class="modal-card-box">
        <span onclick="closeModal()" class="close-modal-btn">&times;</span>
        <div id="modalBody"></div>
    </div>
</div>

<script>
function showDetail(id) {
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = '<p>Loading...</p>';
    document.getElementById('moduleModal').style.display = "block";

    fetch(`/student/modules/${id}/json`)
        .then(res => res.json())
        .then(data => {
            let firstContent = data.contents[0] || {};
            let videoId = firstContent.video_url ? firstContent.video_url.split('v=')[1]?.split('&')[0] : null;
            let videoHtml = videoId ? `<iframe width="100%" height="280" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe>` : `<div style="height:280px; background:#eee; border-radius:10px; display:flex; align-items:center; justify-content:center;">Video Tidak Tersedia</div>`;

            modalBody.innerHTML = `
                <div class="modal-split">
                    {{-- SISI KIRI: MEDIA (VIDEO, PDF ORANYE, TAGS, INFO) --}}
                    <div class="modal-side-media">
                        <div class="video-container">${videoHtml}</div>
                        ${firstContent.file_path ? `
                            <a href="/storage/${firstContent.file_path}" target="_blank" class="pdf-btn">
                                <i class="ri-file-pdf-fill"></i> Download PDF Materi
                            </a>
                        ` : ''}
                        <div class="modal-tags-row">
                            <span class="m-tag">${data.grade_category?.grade || 'Kelas'}</span>
                            <span class="m-tag">${data.subject_category?.subject || 'Materi'}</span>
                        </div>
                        <div class="modal-footer-info">@ ${data.teacher?.name || 'Admin'} <br> Tgl: ${new Date(data.created_at).toLocaleDateString('id-ID')}</div>
                    </div>

                    {{-- SISI KANAN: TEKS --}}
                    <div class="modal-side-text">
                        <h2 class="modal-title-text">${data.title}</h2>
                        <div class="modal-scroll">
                            <p><strong>Deskripsi:</strong></p>
                            <p style="margin-bottom:15px; color:#666;">${data.desc}</p>
                            <hr style="border:0; border-top:1px solid #ddd; margin-bottom:15px;">
                            <p><strong>Detail Materi:</strong></p>
                            <p style="color:#666;">${firstContent.content || 'Isi materi tidak tersedia.'}</p>
                        </div>
                    </div>
                </div>`;
        });
}
function closeModal() { document.getElementById('moduleModal').style.display = "none"; }
</script>


@endsection
