@extends('layouts.app')

@section('content')

    {{-- 🔔 LOGIN SUCCESS ALERT --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                background: '#393E46',
                color: '#ffffff',
                backdrop: `
                rgba(0,0,0,0.4)
                url("{{ asset('images/nyan-cat.gif') }}")
                left top
                no-repeat
            `
            });
        </script>
    @endif

    <section class="hero">
        <h1>Start <span>Learning.</span> Keep Growing.</h1>
        <p>RPLearn is designed to support vocational students <br>in developing real-world skills through structured and
            guided learning.</p>

        <div class="features-card">
            <div class="feat-card"><i class="ri-book-open-line"></i><span>Learning Modules</span></div>
            <div class="feat-card"><i class="ri-bookmark-line"></i><span>Dictionary</span></div>
            <div class="feat-card"><i class="ri-question-line"></i><span>FAQ</span></div>
        </div>
    </section>

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
                {{-- HAPUS onchange="this.form.submit()" DI SINI --}}
                <select name="grade_id">
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
                {{-- HAPUS onchange="this.form.submit()" DI SINI --}}
                <select name="subject_id">
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

    {{-- CEK APAKAH SEDANG ADA FILTER AKTIF DI URL --}}
    @php
        $isFiltering = request('search') || request('grade_id') || request('subject_id');
    @endphp

    {{-- ================= KONTINER UTAMA MODUL ================= --}}
    <div id="moduleCardsContainer">

        {{-- ================= TUGAS CARD 14: TOP 3 MODULES ================= --}}
        {{-- INI BUNGKUSAN YANG BENAR UNTUK TOP 3, DENGAN ID YANG SESUAI --}}
        <div id="top3Section" style="display: {{ $isFiltering ? 'none' : 'block' }};">
            <h3 style="text-align: center; margin-bottom: 20px;">Top 3 Modul Terfavorit <i class="ri-fire-fill" style="color: #F6973F;"></i></h3>

            <div class="module-cards">
                @forelse ($topModules as $module)
                    <div class="module-card">
                        <div class="card-header-row">
                            <span class="module-meta-text" >
                                {{ $module->gradeCategory->grade ?? '-' }} |
                                {{ $module->subjectCategory->subject ?? '-' }}
                            </span>

                            <div class="media-icons-row">
                                @if ($module->contents->whereNotNull('video_url')->count())
                                    <i class="ri-youtube-fill text-red"></i>
                                @endif
                                @if ($module->contents->whereNotNull('file_path')->count())
                                    <i class="ri-file-pdf-2-fill"></i>
                                @endif
                            </div>
                        </div>

                        <h3 class="module-title" onclick="showDetail({{ $module->id }})" style="text-align: center; cursor: pointer; margin-top: 15px;">
                            {{ $module->title }}
                        </h3>

                        <p class="module-desc-text" style="margin-top: 10px;">
                            {{ Str::limit($module->desc, 80) }}
                        </p>

                        <div class="author-label" style="margin-top: 15px; margin-bottom: 15px;">
                            <i class="ri-user-3-line"></i>
                            {{ $module->teacher->username ?? 'Admin' }}
                        </div>

                        {{-- Ceklis Card 14: Ubah tombol jadi "Lihat detail Modul" --}}
                        <button class="btn-pelajari-orange"
                            onclick="showDetail({{ $module->id }})" style="background: linear-gradient(135deg, #F6973F, #D65A31); width: 100%;">
                            Lihat detail Modul <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                @empty
                    <p style="grid-column: 1 / -1; text-align:center;">
                        Belum ada modul terpopuler.
                    </p>
                @endforelse
            </div>

            {{-- TOMBOL PANAH BAWAH JUGA MASUK KE SINI --}}
            <div style="text-align: center; margin: 40px 0;">
                <p style="margin-bottom: 10px; color: #888; font-weight: 500;">Jelajahi Keseluruhan Modul</p>
                <button id="toggleAllModulesBtn" style="background: #393E46; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 1.5rem; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="ri-arrow-down-line"></i>
                </button>
            </div>
        </div> {{-- AKHIR DARI BUNGKUSAN TOP 3 --}}

        {{-- ================= TUGAS CARD 14: KESELURUHAN MODUL ================= --}}
        <div id="allModulesSection" style="display: {{ $isFiltering ? 'block' : 'none' }};">
            @include('partials._module_list')
        </div>

    </div>
</section>

    {{-- DICTIONARY --}}
    <section class="dictionary-section reveal" id="dictionary-section">
        <h2>Dict<span>io</span>nary</h2>
        <p class="dictionary-desc">
            Learn common technical terms used in vocational learning.
        </p>

        <div class="dictionary-search">
            <i class="ri-search-line"></i>
            <input type="text" id="dictionarySearch" name="dictionary_search" placeholder="Search terms..." />
        </div>

        <div class="dictionary-table-wrapper reveal">
            @php
                $grouped = $dictionaries->groupBy(function ($item) {
                    return strtoupper(substr($item->term, 0, 1));
                });
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
                                            <span class="dictionary-term clickable-term"
                                                data-term="{{ $dictionary->term }}"
                                                data-definition="{{ $dictionary->definition }}">
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
                    @forelse ($faqs ?? [] as $faq)
                        <div class="faq-item">
                            <button type="button" class="faq-question">
                                {{ $faq->question }}
                                <i class="ri-arrow-right-s-line icon"></i>
                            </button>
                            <div class="faq-answer">
                                @if ($faq->answer)
                                    {{ $faq->answer->answer }}
                                @else
                                    <em>Belum ada jawaban dari guru.</em>
                                @endif
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
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" value="{{ auth()->user()->username }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Guru Tertuju</label>
                            <select name="teacher_id" required>
                                <option value="">Pilih Guru</option>
                                @foreach ($teachers ?? [] as $teacher)
                                    <option value="{{ $teacher->id }}">
                                        {{ $teacher->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Judul Pertanyaan</label>
                            <input type="text" name="title" placeholder="Contoh: Masalah Login" required>
                        </div>
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


    {{-- 5. MODALS --}}
    @push('modals')
        <div id="moduleModal" class="modal-overlay">
            <div class="modal-card-box">
                <span onclick="closeModal()" class="close-modal-btn">&times;</span>
                <div id="modalBody"></div>
            </div>
        </div>
    @endpush

    @push('modals')
        <div class="dictionary-modal" id="dictionaryModal" aria-hidden="true">
            <div class="dictionary-modal-overlay"></div>
            <div class="dictionary-modal-box">
                <button class="dictionary-modal-close" id="dictionaryModalClose">&times;</button>
                <h3 class="dictionary-modal-term" id="dictionaryModalTerm"></h3>
                <p class="dictionary-modal-definition" id="dictionaryModalDefinition"></p>
            </div>
        </div>
    @endpush

    @push('scripts')
        {{-- JAVASCRIPT MASTER --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- A. AJAX SEARCH MODUL (Instan & Dinamis) ---
                const filterForm = document.getElementById('moduleFilterForm');
                const allModulesSection = document.getElementById('allModulesSection');
                const top3Section = document.getElementById('top3Section'); // Mengambil div Top 3 yang sekarang sudah BENAR
                const searchInput = document.getElementById('moduleSearchInput');
                const toggleBtn = document.getElementById('toggleAllModulesBtn');

                // Kita cari select elemen berdasarkan formnya biar aman
                const gradeSelect = filterForm.querySelector('select[name="grade_id"]');
                const subjectSelect = filterForm.querySelector('select[name="subject_id"]');

                function fetchModules() {
                    const params = new URLSearchParams(new FormData(filterForm)).toString();

                    // Cek apakah user sedang melakukan pencarian/filter
                    const isFiltering = searchInput.value.trim() !== '' ||
                                        (gradeSelect && gradeSelect.value !== '') ||
                                        (subjectSelect && subjectSelect.value !== '');

                    if (isFiltering) {
                        // Jika sedang difilter: Sembunyikan Top 3, Tampilkan hasil
                        if(top3Section) top3Section.style.display = 'none';
                        if(allModulesSection) allModulesSection.style.display = 'block';
                    } else {
                        // Jika filter kosong: Munculkan Top 3, Sembunyikan daftar bawah
                        if(top3Section) top3Section.style.display = 'block';
                        if(allModulesSection) allModulesSection.style.display = 'none';
                        if(toggleBtn) {
                            toggleBtn.innerHTML = '<i class="ri-arrow-down-line"></i>';
                            toggleBtn.style.background = '#393E46';
                        }
                    }

                    if(allModulesSection) allModulesSection.style.opacity = '0.5';

                    fetch(`${window.location.pathname}?${params}&ajax=1`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(res => res.text())
                        .then(html => {
                            if(allModulesSection) {
                                allModulesSection.innerHTML = html;
                                allModulesSection.style.opacity = '1';
                            }
                        });
                }

                if(searchInput) searchInput.addEventListener('input', debounce(fetchModules, 300));
                if(gradeSelect) gradeSelect.addEventListener('change', fetchModules);
                if(subjectSelect) subjectSelect.addEventListener('change', fetchModules);
                if(filterForm) filterForm.addEventListener('submit', (e) => e.preventDefault());

                function debounce(func, timeout = 300) {
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => { func.apply(this, args); }, timeout);
                    };
                }

                // --- B. LOGIKA TOMBOL PANAH BAWAH ---
                if(toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        if (allModulesSection.style.display === 'none') {
                            allModulesSection.style.display = 'block';
                            this.innerHTML = '<i class="ri-arrow-up-line"></i>';
                            this.style.background = '#F6973F'; // Ubah warna saat aktif
                        } else {
                            allModulesSection.style.display = 'none';
                            this.innerHTML = '<i class="ri-arrow-down-line"></i>';
                            this.style.background = '#393E46'; // Kembali ke warna awal
                        }
                    });
                }

                // --- C. DICTIONARY MODAL & SEARCH ---
                const dictModal = document.getElementById('dictionaryModal');
                const modalTerm = document.getElementById('dictionaryModalTerm');
                const modalDefinition = document.getElementById('dictionaryModalDefinition');
                const dictSearchInput = document.getElementById('dictionarySearch');

                document.addEventListener('click', function(e) {
                    const term = e.target.closest('.clickable-term');
                    if (!term) return;

                    modalTerm.textContent = term.dataset.term;
                    modalDefinition.textContent = term.dataset.definition;
                    dictModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });

                window.closeDictModal = function() {
                    dictModal.classList.remove('active');
                    document.body.style.overflow = '';
                };

                const closeBtn = document.getElementById('dictionaryModalClose');
                if(closeBtn) closeBtn.addEventListener('click', closeDictModal);
                const overlay = document.querySelector('.dictionary-modal-overlay');
                if(overlay) overlay.addEventListener('click', closeDictModal);

                if(dictSearchInput) {
                    dictSearchInput.addEventListener('input', function() {
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
                }

                // --- D. FAQ ---
                const faqList = document.getElementById('faq-list');
                const faqSearchInput = document.getElementById('faq-search');

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

                let faqDelay = null;
                if(faqSearchInput) {
                    faqSearchInput.addEventListener('keyup', function() {
                        clearTimeout(faqDelay);
                        faqDelay = setTimeout(() => {
                            fetch(`/faq/search?search=${this.value}`)
                                .then(res => res.json())
                                .then(data => {
                                    faqList.innerHTML = '';
                                    if (data.length === 0) {
                                        faqList.innerHTML = `<p style="text-align:center;color:#888;">FAQ tidak ditemukan</p>`;
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
                }
            });

            // MODULE DETAIL MODAL FUNCTION
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
                            videoElement = `<iframe width="100%" height="280" src="https://www.youtube.com/embed/${vId}" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe>`;
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
                                        <i class="fa-solid fa-heart love-btn ${data.isLiked ? 'liked' : ''}" data-id="${data.id}"></i>
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
        </script>

        {{-- FORM TANYA GURU & LIKES SCRIPT --}}
        <script>
            document.addEventListener('submit', function(e) {
                if (e.target && e.target.getAttribute('action') === '/faq') {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    fetch('/faq', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            alert(res.message);
                            e.target.reset();
                        })
                        .catch(() => alert('Gagal mengirim pertanyaan'));
                }
            });

            document.addEventListener('click', function (e) {
                if (!e.target.classList.contains('love-btn')) return;

                const button = e.target;
                const moduleId = button.dataset.id;

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
                    if (data.liked) button.classList.add('liked');
                    else button.classList.remove('liked');
                })
                .catch(() => button.classList.toggle('liked'));
            });
        </script>
    @endpush
@endsection
