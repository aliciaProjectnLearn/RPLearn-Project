@extends('layouts.app')

@section('content')

<section class="module-section" id="module-section">
    <h1>Modul <span>Tersimpan</span></h1>

    {{-- SEARCH ONLY --}}
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
    </form>

    {{-- ================= KONTINER UTAMA MODUL ================= --}}
    <div class="module-cards" id="moduleCardsContainer">
        @include('partials._module_saved', ['modules' => $modules])
    </div> 
</section>

{{-- ================= SCRIPT ================= --}}
@push('modals')
    <div id="moduleModal" class="modal-overlay">
        <div class="modal-card-box">
            <span onclick="closeModal()" class="close-modal-btn">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>
@endpush

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {

    const filterForm = document.getElementById('moduleFilterForm');
    const moduleContainer = document.getElementById('moduleCardsContainer');
    const searchInput = document.getElementById('moduleSearchInput');

    function fetchModules() {
        const params = new URLSearchParams(new FormData(filterForm)).toString();
        moduleContainer.style.opacity = '0.5';

        fetch(`${window.location.pathname}?${params}&ajax=1`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            moduleContainer.innerHTML = html;
            moduleContainer.style.opacity = '1';
        });
    }

    searchInput.addEventListener('input', debounce(fetchModules, 300));
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
</script>

<script>
function showDetail(id) {
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = '<p class="text-center p-5">Memuat materi...</p>';
    document.getElementById('moduleModal').style.display = "flex";

    fetch(`/student/modules/${id}/json`)
    .then(res => res.json())
    .then(data => {

        let content = data.contents[0] || {};
        let videoElement = '';

        if (content.video_url) {
            let vId = content.video_url.split('v=')[1]?.split('&')[0];
            videoElement =
            `<iframe width="100%" height="280"
                src="https://www.youtube.com/embed/${vId}"
                frameborder="0" allowfullscreen
                style="border-radius:10px;"></iframe>`;
        }
        else if (content.file_path && content.file_path.endsWith('.mp4')) {
            videoElement =
            `<video width="100%" height="280" controls style="border-radius:10px; background:#000;">
                <source src="/storage/${content.file_path}" type="video/mp4">
                Browser kamu tidak mendukung video player.
            </video>`;
        }
        else {
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
<div class="tag-group">
<span class="m-tag">${data.grade_category?.grade_name || 'Umum'}</span>
<span class="m-tag">${data.subject_category?.subject_name || 'Materi'}</span>
</div>

    <div class="icon-group">
        <i class="${data.isSaved ? 'ri-bookmark-fill saved' : 'ri-bookmark-line'} save-btn"
            data-id="${data.id}">
        </i>
    </div>
</div>
</div>

<div class="modal-side-text">
<h2 class="modal-title-text">${data.title}</h2>
<div class="modal-scroll">
<p style="font-size: 0.8rem;">${data.desc}</p>
</div>
</div>
</div>`;
    });
}

function closeModal() {
    document.getElementById('moduleModal').style.display = "none";
}
</script>

<script>
document.addEventListener('click', function (e) {

    if (!e.target.classList.contains('save-btn')) return;

    const button = e.target;
    const moduleId = button.dataset.id;

    const isSaved = button.classList.contains('saved');

    // ubah tampilan dulu biar responsif
    button.classList.toggle('saved');
    button.classList.toggle('ri-bookmark-fill');
    button.classList.toggle('ri-bookmark-line');

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
            button.classList.add('saved','ri-bookmark-fill');
            button.classList.remove('ri-bookmark-line');
        } else {
            button.classList.remove('saved','ri-bookmark-fill');
            button.classList.add('ri-bookmark-line');
        }
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