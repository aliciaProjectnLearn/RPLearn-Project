@extends('layouts.app')

@section('content')

<section class="module-section" id="module-section">
    <h1>Cari <span>Modul</span> Belajarmu!</h1>

    {{-- SEARCH & FILTER --}}
    <form id="moduleFilterForm"
      action="{{ url()->current() }}"
      method="GET"
      class="module-filter-form">


        <div class="module-search">
            <i class="ri-search-line"></i>
            <input type="text" name="search"
                id="moduleSearchInput"
                placeholder="Mau belajar apa hari ini?"
                value="{{ request('search') }}">
        </div>

        <div class="filter-group-modern">
            {{-- Kelas --}}
            <div class="custom-select-wrapper">
                <i class="ri-government-line select-icon"></i>
                <select name="grade_id" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}"
                            {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->grade }}
                        </option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line arrow-icon"></i>
            </div>

            {{-- Materi --}}
            <div class="custom-select-wrapper">
                <i class="ri-book-3-line select-icon"></i>
                <select name="subject_id" onchange="this.form.submit()">
                    <option value="">Semua Materi</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->subject }}
                        </option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line arrow-icon"></i>
            </div>
        </div>
    </form>

    {{-- MODULE CARDS --}}
    <div class="module-cards" id="moduleCardsContainer">
        @forelse ($modules as $module)
            <div class="module-card">

                <div class="card-header-row">
                    <span class="module-meta-text" >
                        {{ $module->gradeCategory->grade ?? '-' }} |
                        {{ $module->subjectCategory->subject ?? '-' }}
                    </span>

                    <div class="media-icons-row">
                        @if ($module->contents->whereNotNull('video_url')->count())
                            <i class="ri-youtube-fill text-red" style="color: var(--orange);"></i>
                        @endif
                        @if ($module->contents->whereNotNull('file_path')->count())
                            <i class="ri-file-pdf-2-fill" style="color: var(--orange);"></i>
                        @endif
                    </div>
                </div>

                <h3 class="module-title" onclick="showDetail({{ $module->id }})" style="padding-bottom: 10px; padding-top: 10px;">
                    {{ $module->title }}
                </h3>
                <p class="module-desc-text">
                    {{ Str::limit($module->desc, 80) }}
                </p>
                <br>
                <div class="foot-module-card">
                    <button class="btn-pelajari-orange"
                        onclick="showDetail({{ $module->id }})" style="background: linear-gradient(135deg, #F6973F, #D65A31);">
                        Pelajari Sekarang <i class="ri-arrow-right-line"></i>
                    </button>
                    <div class="author-label" style="font-size: 0.9rem; color: var(--light);">
                        <i class="ri-user-3-line" style="margin-right: 5px; color: var(--orange);"></i>
                        {{ $module->teacher->username ?? 'Admin' }}
                    </div>
                </div>

                

            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align:center;">
                Modul tidak ditemukan
            </p>
        @endforelse
    </div>
</section>

{{-- ================= SCRIPT ================= --}}
    {{-- 5. MODALS --}}
    @push('modals')
        <div id="moduleModal" class="modal-overlay">
            <div class="modal-card-box">
                <span onclick="closeModal()" class="close-modal-btn">&times;</span>
                <div id="modalBody"></div>
            </div>
        </div>
    @endpush

    @push('scripts')
        {{-- JAVASCRIPT MASTER --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- A. AJAX SEARCH MODUL (Instan tanpa Reload) ---
                const filterForm = document.getElementById('moduleFilterForm');
                const moduleContainer = document.getElementById('moduleCardsContainer');
                const searchInput = document.getElementById('moduleSearchInput');
                const selects = filterForm.querySelectorAll('select');

                function fetchModules() {
                    const params = new URLSearchParams(new FormData(filterForm)).toString();
                    moduleContainer.style.opacity = '0.5';

                    fetch(`${window.location.pathname}?${params}&ajax=1`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.text())
                        .then(html => {
                            moduleContainer.innerHTML = html;
                            moduleContainer.style.opacity = '1';
                        });
                }

                searchInput.addEventListener('input', debounce(fetchModules, 300));
                selects.forEach(select => select.addEventListener('change', fetchModules));
                filterForm.addEventListener('submit', (e) => e.preventDefault());

                function debounce(func, timeout = 300) {
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => {
                            func.apply(this, args);
                        }, timeout);
                    };
                }
            });

            // MODULE DETAIL MODAL
            function showDetail(id) {
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = '<p class="text-center p-5">Memuat materi...</p>';
                document.getElementById('moduleModal').style.display = "flex";

                fetch(`/student/modules/${id}/json`)
                    .then(res => res.json())
                    .then(data => {
                        let content = data.contents[0] || {};
                        let videoElement = '';

                        // Cek untuk memeriksa link YouTube atau file Video asli
                        if (content.video_url) {
                            let vId = content.video_url.split('v=')[1]?.split('&')[0];
                            videoElement =
                                `<iframe width="100%" height="280" src="https://www.youtube.com/embed/${vId}" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe>`;
                        } else if (content.file_path && content.file_path.endsWith('.mp4')) {
                            videoElement = `<video width="100%" height="280" controls style="border-radius:10px; background:#000;">
                                    <source src="/storage/${content.file_path}" type="video/mp4">
                                        Browser kamu tidak mendukung video player.
                                </video>`;
                        } else {
                            videoElement = `<div class="no-video-placeholder">No Video Available</div>`;
                        }

                        modalBody.innerHTML = `
            <div class="modal-split">
                <div class="modal-side-media">
                    <div class="video-container">${videoElement}</div>
                    ${content.file_path && content.file_path.endsWith('.pdf') ?
                        `<a href="/storage/${content.file_path}" target="_blank" class="pdf-btn">
                                                    <i class="ri-file-pdf-line"></i> Download PDF Materi
                                                    </a>` : ''}
                            <div class="modal-tags-row">
                        <span class="m-tag">${data.grade_category?.grade_name || 'Umum'}</span>
                        <span class="m-tag">${data.subject_category?.subject_name || 'Materi'}</span>

                        <i class="fa-solid fa-heart love-btn ${data.isLiked ? 'liked' : ''}" 
                            data-id="${data.id}">
                        </i>


                    </div>
                    </div>
                    <div class="modal-side-text">
                    <h2 class="modal-title-text">${data.title}</h2>
                    <div class="modal-scroll"><p>${data.desc}</p></div>
                    </div>
                    </div>`;
                    });
            }

            function closeModal() {
                document.getElementById('moduleModal').style.display = "none";
            }

            function openModule(moduleId) {
                fetch(`/student/modules/${moduleId}/json`)
                    .then(response => response.json())
                    .then(data => {
                        // 1. Isi Judul & Deskripsi di Modal
                        document.getElementById('modalTitle').innerText = data.title;
                        document.getElementById('modalDesc').innerText = data.desc;

                        // 2. Cari konten Video & PDF dari relasi contents
                        const video = data.contents.find(c => c.type === 'video');
                        const pdf = data.contents.find(c => c.type === 'pdf');

                        // 3. Update Video Player
                        const videoIframe = document.getElementById('videoPlayer');
                        videoIframe.src = video ? `/storage/${video.file_path}` : '';

                        // 4. Update Tombol Download PDF
                        const pdfBtn = document.getElementById('downloadPdfBtn');
                        if (pdf) {
                            pdfBtn.href = `/storage/${pdf.file_path}`;
                            pdfBtn.style.display = 'block';
                        } else {
                            pdfBtn.style.display = 'none';
                        }
                    });
            }
        </script>

    <script>
document.addEventListener('click', function (e) {

    if (!e.target.classList.contains('love-btn')) return;

    const button = e.target;
    const moduleId = button.dataset.id;
    const isLiked = button.classList.contains('liked');

    button.classList.toggle('liked');

    fetch(`/modules/${moduleId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.liked) {
            button.classList.add('liked');
        } else {
            button.classList.remove('liked');
        }
    })
    .catch(() => {
        button.classList.toggle('liked');
    });
});
</script>

@endpush
<style>
    .module-section {
        padding-top: 1rem;
    }
</style>
@endsection