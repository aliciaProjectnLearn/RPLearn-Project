@extends('layouts.app')

@section('content')
    <div class="admin-spacer"></div>

    <div class="page-content px-4">
        <div class="admin-card-box faq-dashboard-card">

            {{-- Header --}}
            <div class="faq-header-modern">
                <div>
                    <h2 class="faq-title">Fitur Tanya Jawab</h2>
                    <p class="faq-subtitle">
                        Kelola pertanyaan dari siswa mengenai materi
                        <span class="text-orange fw-700">RPLearn</span>
                    </p>
                </div>

                <div class="faq-badges">

                    <a href="{{ route('teacher.faq.index') }}"
                        class="badge-modern {{ !request('filter') ? 'active-all' : '' }}">
                        {{ $totalCount }} Semua
                    </a>

                    <a href="{{ route('teacher.faq.index', ['filter' => 'pending']) }}"
                        class="badge-modern pending {{ request('filter') == 'pending' ? 'active' : '' }}">
                        {{ $pendingCount }} Pending
                    </a>

                    <a href="{{ route('teacher.faq.index', ['filter' => 'answered']) }}"
                        class="badge-modern answered {{ request('filter') == 'answered' ? 'active' : '' }}">
                        {{ $answeredCount }} Terjawab
                    </a>

                </div>


            </div>

            <form method="GET" action="{{ route('teacher.faq.index') }}" class="faq-search-form">

                {{-- Supaya filter tetap kebawa --}}
                @if (request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif

                <div class="faq-search-wrapper">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, isi pertanyaan, atau nama siswa..." class="faq-search-input">

                    <button type="submit" class="faq-search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>


            {{-- List FAQ --}}
            @foreach ($questions as $q)
                <div class="faq-item-modern {{ $q->status == 'answered' ? 'faq-status-answered' : 'faq-status-pending' }}">

                    <div class="faq-top">
                        <div class="faq-left">
                            <div class="faq-icon">
                                <i class="fa-solid fa-circle-question"></i>
                            </div>

                            <div>
                                <h5 class="faq-question-title">{{ $q->title }}</h5>
                                <small class="faq-meta">
                                    Oleh: <b>{{ $q->student->name ?? 'Siswa' }}</b>
                                    <span class="mx-1">|</span>
                                    Modul:
                                    <span class="text-orange fw-600">
                                        {{ $q->module->title ?? 'Umum' }}
                                    </span>
                                </small>
                            </div>
                        </div>

                        <div class="faq-time">
                            {{ $q->created_at?->diffForHumans() ?? 'Baru saja' }}
                        </div>
                    </div>

                    <div class="faq-question-box">
                        "{{ $q->question }}"
                    </div>

                    @if ($q->status == 'pending')
                        <form action="{{ route('teacher.faq.answer', $q->id) }}" method="POST" class="faq-form-modern">
                            @csrf
                            <textarea name="answer" class="input-modern" rows="2" placeholder="Tulis jawaban resmi anda di sini..." required></textarea>

                            <button type="submit" class="btn-save-modern">
                                <i class="fa-solid fa-paper-plane me-1"></i> Balas
                            </button>
                        </form>
                    @else
                        {{-- ANSWER --}}
                        <div class="faq-answer-modern position-relative">

                            {{-- Tombol di kanan atas --}}
                            <div class="faq-answer-actions">
                                <button type="button" class="btn-edit-answer" onclick="toggleEdit({{ $q->answer->id }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="{{ route('teacher.faq.delete', $q->answer->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin mau hapus jawaban ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete-answer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Label --}}
                            <div class="faq-answer-label mb-2">
                                <i class="fa-solid fa-reply"></i>
                                JAWABAN ANDA
                            </div>

                            {{-- Text --}}
                            <p id="answer-text-{{ $q->answer->id }}"><br>
                                {{ $q->answer->answer }}
                            </p>

                            {{-- Form Edit --}}
                            <form action="{{ route('teacher.faq.update', $q->answer->id) }}" method="POST"
                                class="edit-form mt-2" id="edit-form-{{ $q->answer->id }}" style="display:none;">
                                @csrf
                                @method('PUT')

                                <textarea name="answer" class="input-modern mb-2" rows="3" required>
                        {{ $q->answer->answer }}
                    </textarea>

                                <button type="submit" class="btn-save-modern">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @endforeach

            @if ($questions->isEmpty())
                <div class="faq-empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p>Belum ada pertanyaan masuk hari ini.</p>
                </div>
            @endif

        </div>
    </div>

    <script>
        function toggleEdit(id) {
            let text = document.getElementById('answer-text-' + id);
            let form = document.getElementById('edit-form-' + id);

            if (form.style.display === "none") {
                form.style.display = "block";
                text.style.display = "none";
            } else {
                form.style.display = "none";
                text.style.display = "block";
            }
        }
    </script>
@endsection
