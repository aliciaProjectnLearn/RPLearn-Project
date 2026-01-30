@extends('layouts.app')

@section('content')
    {{-- Flash success popup --}}
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    {{-- ================= HERO ================= --}}
    <section class="hero">
        <h1>Start <span>Learning.</span> Keep Growing.</h1>
        <p>
            RPLearn is designed to support vocational students <br>
            in developing real-world skills through structured and guided learning.
        </p>

        <div class="features-card">
            <div class="feat-card">
                <i class="ri-book-open-line"></i>
                <span>Learning Modules</span>
            </div>
            <div class="feat-card">
                <i class="ri-bookmark-line"></i>
                <span>Dictionary</span>
            </div>
            <div class="feat-card">
                <i class="ri-question-line"></i>
                <span>FAQ</span>
            </div>
        </div>
    </section>

    {{-- ================= MODULE ================= --}}
    <section class="module-section reveal" id="module-section">
        <h1>Cari <span>Modul</span> Belajarmu!</h1>

        <div class="module-search">
            <i class="ri-search-line"></i>
            <input type="text" placeholder="Search modules...">
        </div>

        <div class="module-cards">
            <div class="module-card">Modul 1</div>
            <div class="module-card">Modul 2</div>
            <div class="module-card">Modul 3</div>
        </div>
    </section>

    {{-- ================= DICTIONARY ================= --}}
    <section class="dictionary-section reveal">
        <h2>Dictionary</h2>
        <p class="dictionary-desc">
            Learn common technical terms used in vocational learning.
        </p>

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

    {{-- ================= FAQ ================= --}}
    <section class="faq-section reveal" id="faq-section">
        <h2>F <span>A</span> Q</h2>
        <div class="question-search">
            <i class="ri-search-line"></i>
            <input type="text" id="faq-search" placeholder="Search question...">
        </div>

        <div class="faq-wrapper">
            {{-- KIRI: FAQ --}}
            <div class="faq-left">
                <div class="faq-list" id="faq-list">
                    @forelse ($faqs as $faq)
                        <div class="faq-item">
                            <button type="button" class="faq-question">
                                {{ $faq->question }}
                                <i class="ri-arrow-right-s-line icon"></i>
                            </button>
                            <div class="faq-answer">
                                {{ $faq->answer->answer }}
                            </div>
                        </div>
                    @empty
                        <p style="text-align:center;color:#888;">
                            FAQ belum tersedia
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- KANAN: FORM TANYA --}}
            <div class="faq-right">
                <div class="faq-form-card">
                    <h3>Tanya Guru</h3>
                    <p class="form-desc">
                        Punya pertanyaan tapi belum ada di FAQ? Kirim langsung ke guru.
                    </p>

                    <form action="/faq" method="POST">
                        @csrf

                        {{-- Nama Siswa --}}
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" value="{{ auth()->user()->username }}" readonly>
                        </div>

                        {{-- Guru --}}
                        <div class="form-group">
                            <label>Guru Tertuju</label>
                            <select name="teacher_id" required>
                                <option value="">Pilih Guru</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">
                                        {{ $teacher->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Judul --}}
                        <div class="form-group">
                            <label>Judul Pertanyaan</label>
                            <input type="text" name="title" placeholder="Contoh: Masalah Login" required>
                        </div>

                        {{-- Pertanyaan --}}
                        <div class="form-group">
                            <label>Pertanyaan</label>
                            <textarea name="question" rows="4" placeholder="Tuliskan pertanyaanmu secara jelas..." required></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            Kirim Pertanyaan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>


    {{-- ================= SCRIPT ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const faqList = document.getElementById('faq-list');
            const searchInput = document.getElementById('faq-search');

            // Accordion logic
            function bindAccordion() {
                document.querySelectorAll('.faq-question').forEach(btn => {
                    btn.onclick = function() {
                        const item = this.parentElement;

                        document.querySelectorAll('.faq-item').forEach(i => {
                            if (i !== item) i.classList.remove('active');
                        });

                        item.classList.toggle('active');
                    };
                });
            }

            bindAccordion();

            // Search logic
            let delay = null;

            searchInput.addEventListener('keyup', function() {
                clearTimeout(delay);

                delay = setTimeout(() => {
                    fetch(`/faq/search?search=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            faqList.innerHTML = '';

                            if (data.length === 0) {
                                faqList.innerHTML = `
                                    <p style="text-align:center;color:#888;">
                                        FAQ tidak ditemukan
                                    </p>
                                `;
                                return;
                            }

                            data.forEach(faq => {
                                faqList.innerHTML += `
                                    <div class="faq-item">
                                        <button type="button" class="faq-question">
                                            ${faq.question}
                                            <i class="ri-arrow-right-s-line icon"></i>
                                        </button>
                                        <div class="faq-answer">
                                            ${faq.answer.answer}
                                        </div>
                                    </div>
                                `;
                            });

                            bindAccordion();
                        });
                }, 300);
            });

        });
    </script>
    <script>
        document.getElementById('ask-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/faq', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    this.reset();
                })
                .catch(() => {
                    alert('Gagal mengirim pertanyaan');
                });
        });
    </script>

{{-- Flash success popup --}}
@if (session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

{{-- 1. HERO SECTION --}}
<section class="hero">
    <h1>Start <span>Learning.</span> Keep Growing.</h1>
    <p>RPLearn is designed to support vocational students <br>in developing real-world skills through structured and guided learning.</p>

    <div class="features-card">
        <div class="feat-card"><i class="ri-book-open-line"></i><span>Learning Modules</span></div>
        <div class="feat-card"><i class="ri-bookmark-line"></i><span>Dictionary</span></div>
        <div class="feat-card"><i class="ri-question-line"></i><span>FAQ</span></div>
    </div>
</section>

{{-- 2. MODULE SECTION --}}
<section class="module-section" id="module-section">
    <h1>Cari <span>Modul</span> Belajarmu!</h1>

    {{-- Form Filter & Search (Tanpa onchange submit agar tidak reload) --}}
    <form action="{{ url()->current() }}" method="GET" class="module-filter-form" id="moduleFilterForm">
        <div class="search-wrapper">
            <div class="module-search">
                <i class="ri-search-line"></i>
                <input type="text" name="search" id="moduleSearchInput" placeholder="Mau belajar apa hari ini?" value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        <div class="filter-group-modern">
            <div class="custom-select-wrapper">
                <i class="ri-government-line select-icon"></i>
                <select name="grade_id" id="gradeSelect">
                    <option value="">Semua Kelas</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->grade }}</option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line arrow-icon"></i>
            </div>

            <div class="custom-select-wrapper">
                <i class="ri-book-3-line select-icon"></i>
                <select name="subject_id" id="subjectSelect">
                    <option value="">Semua Materi</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject }}</option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line arrow-icon"></i>
            </div>
        </div>
    </form>

    {{-- Container Kartu Modul (Memanggil partial dengan path yang benar) --}}
    <div class="module-cards" id="moduleCardsContainer">
        @include('partials._module_list')
    </div>
</section>

{{-- 3. FAQ SECTION --}}
<section class="faq-section" id="faq-section">
    <h2>F <span>A</span> Q</h2>
    <div class="faq-box">Apa itu RPLearn?</div>
    <div class="faq-box">Bagaimana cara belajar?</div>
</section>

{{-- 4. DICTIONARY SECTION --}}
<section class="dictionary-section reveal">
    <h2>Dict<span>io</span>nary</h2>
    <p class="dictionary-desc">Learn common technical terms used in vocational learning.</p>
    <div class="dictionary-search">
        <i class="ri-search-line"></i>
        <input type="text" id="dictionarySearch" placeholder="Search terms..." />
    </div>

    <div class="dictionary-table-wrapper reveal">
        @php
            $grouped = $dictionaries->groupBy(fn($item) => strtoupper(substr($item->term, 0, 1)));
        @endphp

        <div class="dictionary-horizontal-wrapper">
            @forelse ($grouped as $letter => $items)
                <div class="dictionary-column dictionary-letter-column">
                    <h3 class="dictionary-letter">{{ $letter }}</h3>
                    <table class="dictionary-table">
                        <tbody>
                            @foreach ($items as $dictionary)
                                <tr class="dictionary-row">
                                    <td>
                                        <span class="dictionary-term clickable-term" data-term="{{ $dictionary->term }}" data-definition="{{ $dictionary->definition }}">
                                            {{ $dictionary->term }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p>Data tidak ditemukan.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- 5. MODALS --}}
<div id="moduleModal" class="modal-overlay">
    <div class="modal-card-box">
        <span onclick="closeModal()" class="close-modal-btn">&times;</span>
        <div id="modalBody"></div>
    </div>
</div>

<div class="dictionary-modal" id="dictionaryModal" aria-hidden="true">
    <div class="dictionary-modal-overlay"></div>
    <div class="dictionary-modal-box">
        <button class="dictionary-modal-close" id="dictionaryModalClose">&times;</button>
        <h3 class="dictionary-modal-term" id="dictionaryModalTerm"></h3>
        <p class="dictionary-modal-definition" id="dictionaryModalDefinition"></p>
    </div>
</div>

@endsection

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
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
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

    function debounce(func, timeout = 300){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // --- B. DICTIONARY MODAL & SEARCH (Kodingan Asli Lo) ---
    const dictModal = document.getElementById('dictionaryModal');
    const modalTerm = document.getElementById('dictionaryModalTerm');
    const modalDefinition = document.getElementById('dictionaryModalDefinition');
    const dictSearchInput = document.getElementById('dictionarySearch');

    document.querySelectorAll('.clickable-term').forEach(item => {
        item.addEventListener('click', function () {
            modalTerm.textContent = this.dataset.term;
            modalDefinition.textContent = this.dataset.definition;
            dictModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    window.closeDictModal = function() {
        dictModal.classList.remove('active');
        document.body.style.overflow = '';
    };

    document.getElementById('dictionaryModalClose').addEventListener('click', closeDictModal);
    document.querySelector('.dictionary-modal-overlay').addEventListener('click', closeDictModal);

    // Dictionary Real-time Filtering
    dictSearchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.dictionary-row').forEach(row => {
            const term = row.querySelector('.dictionary-term').textContent.toLowerCase();
            row.style.display = term.includes(keyword) ? '' : 'none';
        });

        document.querySelectorAll('.dictionary-letter-column').forEach(column => {
            const visibleRows = column.querySelectorAll('.dictionary-row:not([style*="display: none"])');
            column.style.display = visibleRows.length === 0 ? 'none' : '';
        });
    });
});

// --- C. MODULE DETAIL MODAL ---
function showDetail(id) {
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = '<p>Loading...</p>';
    document.getElementById('moduleModal').style.display = "block";
    fetch(`/student/modules/${id}/json`)
        .then(res => res.json())
        .then(data => {
            let content = data.contents[0] || {};
            let vId = content.video_url ? content.video_url.split('v=')[1]?.split('&')[0] : null;
            let video = vId ? `<iframe width="100%" height="280" src="https://www.youtube.com/embed/${vId}" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe>` : `<div style="height:280px; background:#eee; border-radius:10px; display:flex; align-items:center; justify-content:center;">No Video</div>`;
            modalBody.innerHTML = `<div class="modal-split">
                <div class="modal-side-media">
                    <div class="video-container">${video}</div>
                    ${content.file_path ? `<a href="/storage/${content.file_path}" target="_blank" class="pdf-btn">Download PDF</a>` : ''}
                    <div class="modal-tags-row"><span class="m-tag">${data.grade_category?.grade || 'Kelas'}</span><span class="m-tag">${data.subject_category?.subject || 'Materi'}</span></div>
                </div>
                <div class="modal-side-text">
                    <h2 class="modal-title-text">${data.title}</h2>
                    <div class="modal-scroll"><p>${data.desc}</p></div>
                </div>
            </div>`;
        });
}
function closeModal() { document.getElementById('moduleModal').style.display = "none"; }
</script>
