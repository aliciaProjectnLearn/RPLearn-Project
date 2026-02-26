@extends('layouts.app')

@section('content')
    <div style="max-width:900px; margin:40px auto;">

        <div class="page-header">

            <div>
                <div class="title">
                    <h2>Per<span>tanya</span>anmu</h2>
                </div>
                <p class="page-subtitle">
                    Kelola pertanyaan yang kamu kirim ke guru
                </p>
            </div>

            <div class="filter-group">

                <a href="{{ route('student.questions.index') }}"
                    class="badge-modern {{ !request('filter') ? 'active-all' : '' }}">
                    {{ $totalCount }} Semua
                </a>

                <a href="{{ route('student.questions.index', ['filter' => 'pending']) }}"
                    class="badge-modern pending {{ request('filter') == 'pending' ? 'active' : '' }}">
                    {{ $pendingCount }} Pending
                </a>

                <a href="{{ route('student.questions.index', ['filter' => 'answered']) }}"
                    class="badge-modern answered {{ request('filter') == 'answered' ? 'active' : '' }}">
                    {{ $answeredCount }} Terjawab
                </a>



            </div>
            <a href="{{ route('student.questions.create') }}" class="btn-add">
                + Tambah
            </a>
        </div>

        <div class="faq-list">

            @forelse($questions as $question)
                <div class="faq-item">

                    <button type="button" class="faq-question">

                        <span class="question-text">
                            {{ $question->question }}
                        </span>

                        <div class="right-section">

                            @if ($question->status === 'answered')
                                <span class="badge success">Terjawab</span>
                            @else
                                <span class="badge pending">Pending</span>
                            @endif

                            <i class="ri-arrow-right-s-line icon"></i>
                        </div>

                    </button>

                    <div class="faq-answer">

                        @if ($question->answer)
                            <div class="answer-box">
                                {{ $question->answer->answer }}
                            </div>
                        @else
                            <em style="color:#999;">Belum ada jawaban dari guru.</em>
                        @endif

                    </div>

                </div>

            @empty
                <div style="padding:30px; text-align:center; color:#888;">
                    Belum ada pertanyaan.
                </div>
            @endforelse

        </div>

    </div>


    {{-- STYLE --}}
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .title h2 {
            font-size: 2rem;
            color: var(--dark-soft);
            font-weight: 700;
        }

        .title h2 span {
            color: var(--orange);
        }

        .page-subtitle {
            margin-top: 5px;
            font-size: 14px;
            color: #666;
        }

        .filter-group {
            display: flex;
            gap: 12px;
        }

        .filter-pill {
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
            background: #f1f1f1;
            color: #444;
            transition: 0.3s ease;
        }

        .filter-pill:hover {
            transform: translateY(-2px);
        }

        .filter-pill.active.blue {
            background: #e8f1ff;
            color: #2f6fed;
        }

        .filter-pill.active.yellow {
            background: #fff3cd;
            color: #b68900;
        }

        .filter-pill.active.green {
            background: #e3fcef;
            color: #0a8754;
        }

        .faq-item {
            background: white;
            border-radius: 14px;
            margin-bottom: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
            overflow: hidden;
            transition: 0.3s;
        }

        .faq-question {
            width: 100%;
            padding: 18px 22px;
            border: none;
            background: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .faq-question:hover {
            background: #fafafa;
        }

        .question-text {
            text-align: left;
        }

        .right-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease, padding 0.3s ease;
            padding: 0 22px;
            font-size: 14px;
            color: #555;
        }

        .faq-item.active .faq-answer {
            max-height: 400px;
            padding: 15px 22px 20px;
        }

        .answer-box {
            background: #f1f8ff;
            padding: 15px;
            border-radius: 10px;
        }

        .badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .success {
            background: #e3fcef;
            color: #0a8754;
        }

        .pending {
            background: #fff3cd;
            color: #b68900;
        }

        .arrow {
            font-size: 18px;
            transition: transform 0.7s ease;
        }

        .faq-item.active .arrow {
            transform: rotate(180deg);
        }

        .btn-add {
            padding: 10px 18px;
            background: linear-gradient(135deg, #F6973F, #f5b041);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(246, 151, 63, 0.3);
            transition: 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(246, 151, 63, 0.4);
        }
    </style>


    {{-- SCRIPT --}}
    <script>
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {

                const item = button.closest('.faq-item');

                // optional: close others (exclusive mode)
                document.querySelectorAll('.faq-item').forEach(i => {
                    if (i !== item) i.classList.remove('active');
                });

                item.classList.toggle('active');
            });
        });
    </script>
@endsection
