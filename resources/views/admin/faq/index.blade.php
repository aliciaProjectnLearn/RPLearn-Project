@extends('layouts.app')

@section('content')
    <div class="admin-spacer" style="height: 60px;"></div>

    <div class="content-body pt-5 px-4">

        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-end mb-4 pb-2 border-bottom">
            <div>
                <h2 class="fw-bold text-dark mb-1">Monitoring FAQ</h2><br>
                <p class="text-secondary m-0">
                    Total: <span class="text-orange fw-bold">{{ $questions->count() }} Pertanyaan</span>
                </p>
            </div>
        </div>

        {{-- Filter & Search --}}
        <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">
            <div class="faq-actions">
                {{-- Filter Status --}}
                <select name="status" onchange="this.form.submit()" class="form-select faq-select shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>
                        Terjawab
                    </option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                </select>

                {{-- Search --}}
                <div class="faq-search-box shadow-sm">
                    <input type="text" name="search" placeholder="Cari judul / pertanyaan..."
                        value="{{ request('search') }}">
                    <button type="submit" class="faq-btn-search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabel FAQ --}}
        <div class="module-card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="rplearn-table align-middle w-100">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">
                                Pertanyaan
                            </th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-center">
                                Penanya
                            </th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-center">
                                Status
                            </th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-center">
                                Penjawab
                            </th>
                            <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-muted">
                                Detail
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($questions as $question)
                            <tr class="module-row border-bottom">

                                {{-- Pertanyaan --}}
                                <td class="ps-4 py-3" style="text-align: left; ">
                                    <div class="fw-bold text-dark" style="font-weight: bold">
                                        {{ $question->title }}
                                    </div>
                                    <small class="text-muted">
                                        {{ Str::limit($question->question, 90) }}
                                    </small>
                                </td>

                                {{-- Penanya --}}
                                <td class="text-center py-3 small fw-600 text-dark">
                                    {{ $question->student->name ?? 'Siswa' }}
                                </td>

                                {{-- Status --}}
                                <td class="text-center py-3">
                                    @if ($question->status === 'answered')
                                        <span class="badge-grade px-3 py-1 rounded-pill small fw-bold"
                                            style="font-size:11px; background-color:rgb(22, 218, 22); color: black">
                                            Terjawab
                                        </span>
                                    @else
                                        <span class="badge-grade px-3 py-1 rounded-pill small fw-bold"
                                            style="font-size:11px; background-color:red; color: white">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                {{-- Penjawab --}}
                                <td class="text-center text-muted small fw-600 py-3">
                                    {{ $question->answer?->teacher?->username ?? 'Belum dijawab' }}
                                </td>

                                {{-- Detail (INI YANG TADI SALAH) --}}
                                <td class="text-end pe-4 py-3">
                                    <a href="javascript:void(0)" class="faq-detail-btn" title="Lihat Detail"
                                        data-title="{{ $question->title }}" data-question="{{ $question->question }}"
                                        data-answer="{{ $question->answer->answer ?? 'Belum ada jawaban.' }}"
                                        data-teacher="{{ $question->answer->teacher->username ?? '-' }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted italic small">
                                    Belum ada data FAQ.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- MODAL FAQ --}}
        @push('modals')
            <div class="faq-modal" id="faqModal" aria-hidden="true">
                <div class="faq-modal-overlay"></div>

                <div class="faq-modal-box">
                    <button class="faq-modal-close" id="faqModalClose">&times;</button>

                    <h3 class="faq-modal-title" id="faqModalTitle"></h3>
                    <p class="faq-modal-question" id="faqModalQuestion"></p>

                    <div class="faq-modal-divider"></div>

                    <p class="faq-modal-answer" id="faqModalAnswer"></p>

                    <small class="faq-modal-meta">
                        Dijawab oleh <strong id="faqModalTeacher"></strong>
                    </small>
                </div>
            </div>
        @endpush

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const modal = document.getElementById('faqModal');
                const modalTitle = document.getElementById('faqModalTitle');
                const modalQuestion = document.getElementById('faqModalQuestion');
                const modalAnswer = document.getElementById('faqModalAnswer');
                const modalTeacher = document.getElementById('faqModalTeacher');

                document.querySelectorAll('.faq-detail-btn').forEach(btn => {
                    btn.addEventListener('click', function() {

                        modalTitle.textContent = this.dataset.title;
                        modalQuestion.textContent = this.dataset.question;
                        modalAnswer.textContent = this.dataset.answer;
                        modalTeacher.textContent = this.dataset.teacher;

                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    });
                });

                function closeModal() {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }

                document.getElementById('faqModalClose').addEventListener('click', closeModal);
                document.querySelector('.faq-modal-overlay').addEventListener('click', closeModal);
            });
        </script>
    @endpush
@endsection
