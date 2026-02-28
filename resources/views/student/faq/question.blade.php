@extends('layouts.app')

@section('content')
    <div style="max-width:900px; margin:40px auto;">

        <div class="page-header">
            <div class="title">
                <h2>Per<span>tanya</span>anmu</h2>
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

            <a href="{{ route('student.questions.create') }}" class="add">
                + Tambah
            </a>
        </div>

        <div class="faq-list">
            @forelse($questions as $question)
                <div class="faq-item">
                    <div class="faq-row">

                        <span class="question-text">
                            {{ $question->question }}
                        </span>

                        <div class="right-section">

                            @if ($question->status === 'answered')
                                <span class="badge success">Terjawab</span>
                            @else
                                <span class="badge pending">Pending</span>
                            @endif

                            {{-- READ (selalu ada) --}}
                            <a href="javascript:void(0)" class="faq-detail-btn icon-btn" title="Lihat Detail"
                                data-title="Detail Pertanyaan" data-question="{{ $question->question }}"
                                data-answer="{{ $question->answer->answer ?? 'Belum ada jawaban dari guru.' }}"
                                data-teacher="{{ $question->answer->teacher->username ?? '-' }}">
                                <i class="ri-eye-line"></i>
                            </a>

                            {{-- EDIT (hanya kalau belum dijawab) --}}
                            @if ($question->status !== 'answered')
                                <a href="javascript:void(0)" class="faq-edit-btn icon-btn edit-btn" title="Edit Pertanyaan"
                                    data-id="{{ $question->id }}" data-title="{{ $question->title }}"
                                    data-question="{{ $question->question }}">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            @endif

                            {{-- DELETE (hanya kalau belum dijawab) --}}
                            @if ($question->status !== 'answered')
                                <form action="{{ route('student.questions.destroy', $question->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="icon-btn delete-btn faq-delete-btn"
                                        data-title="{{ $question->title }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            @endif

                        </div>

                    </div>
                </div>
            @empty
                <div style="padding:30px; text-align:center; color:#888;">
                    Belum ada pertanyaan.
                </div>
            @endforelse
        </div>
    </div>


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
    @push('modals')
        <div class="faq-modal" id="editModal" aria-hidden="true">
            <div class="faq-modal-overlay"></div>

            <div class="faq-modal-box">
                <button class="faq-modal-close" id="editModalClose">&times;</button>

                <h3>Edit Pertanyaan</h3>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <label style="font-weight:600;">Judul Pertanyaan</label>
                    <input type="text" name="title" id="editTitle"
                        style="width:100%; padding:10px; border-radius:8px; margin:8px 0 15px 0; border:1px solid #ddd;">

                    {{-- Isi --}}
                    <label style="font-weight:600;">Pertanyaan</label>
                    <textarea name="question" id="editTextarea" rows="4"
                        style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd;"></textarea>

                    <br><br>

                    <button type="submit"
                        style="padding:10px 20px; border:none; border-radius:8px; background:#f6973f; color:white; font-weight:600;">
                        Update Pertanyaan
                    </button>
                </form>
            </div>
        </div>
    @endpush


    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .title h2{
            font-size: 3rem;
            text-align: left;
            color: var(--dark-soft);
            font-weight: 700;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .title h2 span {
            color: var(--orange);
        }

        .filter-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 5px;
        }

        .faq-item {
            background: white;
            border-radius: 14px;
            margin-bottom: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
        }

        .faq-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
        }

        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8f1ff;
            color: #2f6fed;
            font-size: 14px;
            text-decoration: none;
            transition: 0.2s;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
        }

        /* MODAL */
        .faq-modal {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .faq-modal.active {
            display: flex;
        }

        .faq-modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .faq-modal-box {
            position: relative;
            background: #fff;
            width: 450px;
            padding: 25px;
            border-radius: 14px;
            z-index: 2;
        }

        .faq-modal-close {
            position: absolute;
            top: 12px;
            right: 16px;
            border: none;
            background: none;
            font-size: 22px;
            cursor: pointer;
        }

        .faq-modal-divider {
            height: 1px;
            background: #eee;
            margin: 15px 0;
        }

        .faq-modal-meta {
            color: #777;
            font-size: 12px;
        }

        .right-section {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .add {
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            background: #deecf5;
            color: #6e93df;
            text-decoration: none;
        }

        /* ICON BUTTON */
        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: #e8f1ff;
            color: #2f6fed;
            transition: 0.2s;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
        }

        .edit-btn {
            background: #fff3cd;
            color: #b68900;
        }

        .delete-btn {
            background: #ffe3e3;
            color: #d90429;
        }

        /* MODAL */
        .dict-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .dict-box {
            background: white;
            width: 420px;
            padding: 25px;
            border-radius: 14px;
            position: relative;
        }

        .dict-close {
            position: absolute;
            top: 12px;
            right: 16px;
            font-size: 22px;
            cursor: pointer;
        }

        .smooth-popup {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6) !important;
        }

        .smooth-title {
            font-weight: 600 !important;
            font-size: 18px !important;
        }

        .smooth-confirm {
            font-weight: 600 !important;
            border-radius: 8px !important;
        }

        .smooth-cancel {
            border-radius: 8px !important;
        }
    </style>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* =========================
                   DETAIL MODAL
                ========================== */
                const modal = document.getElementById('faqModal');
                const modalTitle = document.getElementById('faqModalTitle');
                const modalQuestion = document.getElementById('faqModalQuestion');
                const modalAnswer = document.getElementById('faqModalAnswer');
                const modalTeacher = document.getElementById('faqModalTeacher');

                document.querySelectorAll('.faq-detail-btn').forEach(btn => {
                    btn.addEventListener('click', function() {

                        modalTitle.textContent = this.dataset.title ?? 'Detail Pertanyaan';
                        modalQuestion.textContent = this.dataset.question ?? '-';
                        modalAnswer.textContent = this.dataset.answer ?? 'Belum ada jawaban.';
                        modalTeacher.textContent = this.dataset.teacher ?? '-';

                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    });
                });

                function closeDetailModal() {
                    if (!modal) return;
                    modal.classList.remove('active')
                    document.body.style.overflow = '';
                }

                const detailCloseBtn = document.getElementById('faqModalClose');
                if (detailCloseBtn) {
                    detailCloseBtn.addEventListener('click', closeDetailModal);
                }

                const detailOverlay = modal?.querySelector('.faq-modal-overlay');
                if (detailOverlay) {
                    detailOverlay.addEventListener('click', closeDetailModal);
                }

                /* =========================
                   EDIT MODAL
                ========================== */
                const editTitle = document.getElementById('editTitle');
                const editModal = document.getElementById('editModal');
                const editTextarea = document.getElementById('editTextarea');
                const editForm = document.getElementById('editForm');

                document.querySelectorAll('.faq-edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {

                        const id = this.dataset.id;
                        const title = this.dataset.title;
                        const question = this.dataset.question;

                        if (!editModal) return;

                        editTitle.value = title ?? '';
                        editTextarea.value = question ?? '';
                        editForm.action = `/student/questions/${id}`;

                        editModal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    });
                });

                function closeEditModal() {
                    if (!editModal) return;
                    editModal.classList.remove('active');
                    document.body.style.overflow = '';
                }

                const editCloseBtn = document.getElementById('editModalClose');
                if (editCloseBtn) {
                    editCloseBtn.addEventListener('click', closeEditModal);
                }

                const editOverlay = editModal?.querySelector('.faq-modal-overlay');
                if (editOverlay) {
                    editOverlay.addEventListener('click', closeEditModal);
                }


                /* =========================
                    DELETE - SWEET ALERT
                ========================= */
                document.querySelectorAll('.faq-delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {

                        const form = this.closest('form');
                        const title = this.dataset.title ?? 'pertanyaan ini';

                        Swal.fire({
                            title: 'Hapus Pertanyaan?',
                            text: `"${title}" akan dihapus permanen.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,

                            width: '380px',
                            padding: '1.8em',
                            borderRadius: '14px',

                            background: '#1f2937',
                            color: '#f1f5f9',

                            confirmButtonColor: '#f6973f',
                            cancelButtonColor: '#374151',

                            backdrop: 'rgba(0,0,0,0.75)',

                            customClass: {
                                popup: 'smooth-popup',
                                title: 'smooth-title',
                                confirmButton: 'smooth-confirm',
                                cancelButton: 'smooth-cancel'
                            }

                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });

                    });
                });

            });
        </script>
    @endpush
@endsection
