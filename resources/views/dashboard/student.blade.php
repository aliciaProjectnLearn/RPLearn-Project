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
        <h1>Start <span>Learning. </span> Keep <br>Growing.</h1>
        <p>Semua materi kelas dalam satu genggaman. RPLearn menyusun modul pembelajaran secara sistematis untuk bantu kamu kuasai keahlian dunia nyata dengan lebih mudah dan terpadu.</p>

        <div class="features-card">
            <div class="feat-card"><i class="ri-book-open-line"></i><span>Kumpulan Modul</span></div>
            <div class="feat-card"><i class="ri-bookmark-line"></i><span>Kamus</span></div>
            <div class="feat-card"><i class="ri-question-line"></i><span>Ruang Tanya</span></div>
        </div>
    </section>

<section class="module-section" id="module-section">

    {{-- HEADER --}}
    <div class="module-section-header">
        <h1>Cari <span>Modul</span> Belajarmu!</h1>
    </div>

    {{-- SEARCH --}}
    <form id="moduleFilterForm"
          action="{{ url()->current() }}"
          method="GET"
          class="module-filter-form">

        <div class="module-search">
            <i class="ri-search-line"></i>
            <input type="text"
                   name="search"
                   id="moduleSearchInput"
                   placeholder="Mau belajar apa hari ini?"
                   value="{{ request('search') }}">
        </div>
    </form>

    {{-- MODULE CARDS --}}
    <div class="module-cards" id="moduleCardsContainer">

        {{-- ✅ pakai partial yang sudah ada fitur isViewed --}}
        @include('partials._module_list', ['modules' => $modules])

    </div>

    {{-- BUTTON LIHAT SEMUA --}}
    <div class="button-more">
        <a href="{{ route('student.modules.index') }}"
           class="btn-more-dictionary">
            Lihat Semua →
        </a>
    </div>

</section>

    {{-- DICTIONARY --}}
    <section class="dictionary-section reveal" id="dictionary-section">
        <h2>Ka<span>m</span>us</h2>

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
                                            <span class="dictionary-term clickable-term" data-term="{{ $dictionary->term }}"
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
        <div class="button-more">
            <a href="{{ route('student.dictionary.index') }}" class="btn-more-dictionary">
                Lihat Semua →
            </a>
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section class="faq-section reveal" id="faq-section">
        <h2>Ruang <span>Tanya</span></h2>

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
                        Punya pertanyaan tapi belum ada di Ruang Tanya? Kirim langsung ke guru.
                    </p>
                    <form action="/student/faq" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" name="student_name" value="{{ auth()->user()->username }}" readonly>
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
                            <input type="text" name="title" placeholder="Contoh: Masalah Login" required
                                style="background: #374151; color: #f9fafb">
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
                // --- A. AJAX SEARCH MODUL (Sederhana) ---
                const filterForm = document.getElementById('moduleFilterForm');
                const moduleCards = document.getElementById('moduleCardsContainer');
                const searchInput = document.getElementById('moduleSearchInput');

                function fetchModules() {
    // Ambil query dari input langsung supaya lebih aman
    const searchValue = searchInput.value;
    const url = new URL(window.location.href);
    
    // Set parameter search
    url.searchParams.set('search', searchValue);
    url.searchParams.set('ajax', '1');

    fetch(url.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.text();
    })
    .then(html => {
        // Pastikan element moduleCards ada di DOM
        if (moduleCards) {
            moduleCards.innerHTML = html;
        }
    })
    .catch(err => console.error('Error fetching modules:', err));
}

                if (searchInput) searchInput.addEventListener('input', debounce(fetchModules, 300));
                if (filterForm) filterForm.addEventListener('submit', (e) => e.preventDefault());

                function debounce(func, timeout = 300) {
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => {
                            func.apply(this, args);
                        }, timeout);
                    };
                }

                // --- B. LOGIKA TOMBOL PANAH BAWAH ---
                // --- B. LOGIKA TOMBOL PANAH BAWAH ---
                const toggleBtn = document.getElementById('toggleBtn');
                const allModulesSection = document.getElementById('allModulesSection');

                if (toggleBtn && allModulesSection) {
                    toggleBtn.addEventListener('click', function() {
                        if (allModulesSection.style.display === 'none') {
                            allModulesSection.style.display = 'block';
                            this.innerHTML = '<i class="ri-arrow-up-line"></i>';
                            this.style.background = '#F6973F';
                        } else {
                            allModulesSection.style.display = 'none';
                            this.innerHTML = '<i class="ri-arrow-down-line"></i>';
                            this.style.background = '#393E46';
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
                if (closeBtn) closeBtn.addEventListener('click', closeDictModal);
                const overlay = document.querySelector('.dictionary-modal-overlay');
                if (overlay) overlay.addEventListener('click', closeDictModal);

                if (dictSearchInput) {
                    dictSearchInput.addEventListener('input', function() {
                        const keyword = this.value.toLowerCase();
                        document.querySelectorAll('.dictionary-row').forEach(row => {
                            const term = row.querySelector('.dictionary-term').textContent
                                .toLowerCase();
                            row.style.display = term.includes(keyword) ? '' : 'none';
                        });

                        document.querySelectorAll('.dictionary-letter-column').forEach(column => {
                            const visibleRows = column.querySelectorAll(
                                '.dictionary-row:not([style*="display: none"])');
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
                if (faqSearchInput) {
                    faqSearchInput.addEventListener('keyup', function() {
                        clearTimeout(faqDelay);
                        faqDelay = setTimeout(() => {
                            fetch(`/faq/search?search=${this.value}`)
                                .then(res => res.json())
                                .then(data => {
                                    faqList.innerHTML = '';
                                    if (data.length === 0) {
                                        faqList.innerHTML =
                                            `<p style="text-align:center;color:#888;">FAQ tidak ditemukan</p>`;
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
                            videoElement =
                                `<iframe width="100%" height="250" src="https://www.youtube.com/embed/${vId}" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe>`;
                        } else if (content.file_path && content.file_path.endsWith('.mp4')) {
                            videoElement = `<video width="100%" height="250" controls style="border-radius:10px; background:#000;">
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
                                    <div class="modal-scroll"><p style="font-size: 0.8rem;">${data.desc}</p></div>
                                </div>
                            </div>`;
                    });
            }

            function closeModal() {
                document.getElementById('moduleModal').style.display = "none";
            }
        </script>

        {{-- FORM TANYA GURU & LIKES SCRIPT (FIXED FAQ AJAX) --}}
        <script>
            // ====== FIX FORM TANYA GURU (FAQ) ======
            document.addEventListener('DOMContentLoaded', function() {
                const faqForm = document.querySelector('form[action="/student/faq"]');

                if (!faqForm) return;

                faqForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = faqForm.querySelector('.btn-submit');
                    const originalText = submitBtn.innerHTML;

                    // Disable tombol biar tidak double submit
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'Mengirim...';

                    try {
                        const formData = new FormData(faqForm);

                        const response = await fetch('/student/faq', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': faqForm.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json' // 🔥 PENTING: biar Laravel return JSON, bukan redirect
                            },
                            body: formData
                        });

                        // Kalau validasi error (422)
                        if (response.status === 422) {
                            const errorData = await response.json();
                            let errorMessages = '';

                            Object.values(errorData.errors).forEach(errArr => {
                                errorMessages += `• ${errArr[0]}\n`;
                            });

                            alert("Gagal mengirim pertanyaan:\n" + errorMessages);
                            return;
                        }

                        // Kalau sukses
                        if (response.ok) {
                            // Tidak pakai res.json() paksa (biar aman kalau controller return text/redirect)
                            alert('Pertanyaan berhasil dikirim ke guru! 🎉');

                            // Reset hanya field input & textarea (nama siswa tetap)
                            faqForm.querySelector('select[name="teacher_id"]').value = '';
                            faqForm.querySelector('input[name="title"]').value = '';
                            faqForm.querySelector('textarea[name="question"]').value = '';
                        } else {
                            alert('Terjadi kesalahan saat mengirim pertanyaan.');
                        }

                    } catch (error) {
                        console.error('FAQ Submit Error:', error);
                        alert('Gagal mengirim pertanyaan. Cek koneksi atau server.');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('click', function(e) {

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
                            button.classList.add('saved', 'ri-bookmark-fill');
                            button.classList.remove('ri-bookmark-line');
                        } else {
                            button.classList.remove('saved', 'ri-bookmark-fill');
                            button.classList.add('ri-bookmark-line');
                        }
                    });
            });
        </script>
    @endpush
@endsection
