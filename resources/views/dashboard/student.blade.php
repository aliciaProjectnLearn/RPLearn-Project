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
@endsection
