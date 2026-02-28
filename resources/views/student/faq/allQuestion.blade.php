@extends('layouts.app')

@section('content')
    <div class="faq-title">
        <h2>Semua <span>Pertanyaan</span></h2>
        <p class="page-subtitle">Pertanyaan dari siswa yang sudah dijawab guru</p>
    </div>

    <div class="faq-controls">
        <div class="faq-search">
            <i class="ri-search-line"></i>
            <input type="text" id="faqSearch" placeholder="Cari pertanyaan...">
        </div>
        <a href="{{ route('student.questions.create') }}" class="add">
            + Tambah
        </a>
    </div>

    <div class="all-faq-wrapper">
        <div class="faq-list">
            @forelse ($faqs ?? [] as $faq)
                <div class="faq-item">
                    <button type="button" class="faq-question">
                        <span>{{ $faq->question }}</span>
                        <i class="ri-arrow-down-s-line icon"></i>
                    </button>

                    <div class="faq-answer">
                        {{ $faq->answer->answer ?? 'Belum ada jawaban.' }}
                    </div>
                </div>
            @empty
                <div class="faq-empty">
                    Belum ada pertanyaan tersedia.
                </div>
            @endforelse
        </div>

    </div>

    <style>
        /* TITLE */
        .faq-title{
            margin-bottom: 3rem;
        }
        .faq-title h2 {
            font-size: 3rem;
            text-align: center;
            color: var(--dark-soft);
            font-weight: 700;
            margin-top: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .faq-title h2 span {
            color: var(--orange);
        }

        .page-subtitle {
            text-align: center
        }

        /* CONTROLS */
        .faq-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 860px;
            margin: 1.5rem auto 1.5rem auto;
            padding: 0 1rem;
            gap: 1rem;
        }

        .faq-search {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 16px;
            flex: 1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: border-color 0.2s;
        }

        .faq-search:focus-within {
            border-color: var(--orange);
        }

        .faq-search i {
            color: #aaa;
            font-size: 1.1rem;
        }

        .faq-search input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            color: #333;
            background: transparent;
        }

        .faq-search input::placeholder {
            color: #bbb;
        }

        .add {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 3px 10px rgba(246, 151, 63, 0.35);
        }

        .add:hover {
            background: #e07e28;
            transform: translateY(-1px);
        }

        /* ITEM */
        .faq-item {
            background: #ffffff;
            max-width: 800px;
            border-radius: 16px;
            margin-bottom: 20px;
            margin-left: 3rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: 0.3s;
        }

        .faq-item:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        /* ACTIVE */
        .faq-item.active {
            border: 1px solid #F6973F;
        }

        /* QUESTION */
        .faq-question {
            width: 100%;
            background: none;
            border: none;
            padding: 22px 26px;
            text-align: left;
            font-size: 15px;
            font-weight: 500;
            align-items: center;
            cursor: pointer;
        }


        .faq-item.active .icon {
            transform: rotate(180deg);
        }

        /* ANSWER */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            padding: 0 26px;
            font-size: 17px;
            color: #444;
            line-height: 1.6;
            transition: all 0.35s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
            padding: 0 26px 24px 26px;
        }

        /* EMPTY */
        .faq-empty {
            text-align: center;
            color: #999;
            margin-top: 30px;
            font-size: 14px;
        }

        /* HIDDEN by search */
        .faq-item.hidden {
            display: none;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                document.querySelectorAll('.faq-question').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const item = this.parentElement;

                        document.querySelectorAll('.faq-item').forEach(i => {
                            if (i !== item) i.classList.remove('active');
                        });

                        item.classList.toggle('active');
                    });
                });

                // Search filter
                const searchInput = document.getElementById('faqSearch');
                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();
                    document.querySelectorAll('.faq-item').forEach(item => {
                        const question = item.querySelector('.faq-question span')?.textContent.toLowerCase() ?? '';
                        const answer = item.querySelector('.faq-answer')?.textContent.toLowerCase() ?? '';
                        item.classList.toggle('hidden', keyword !== '' && !question.includes(keyword) && !answer.includes(keyword));
                    });
                });

            });
        </script>
    @endpush
@endsection
