@extends('layouts.app')

@section('content')
{{-- Gunakan spacer khusus biar gak "nyelam" di ThinkPad T460s lo --}}
<div class="admin-spacer"></div>

<div class="page-content px-4">
    <div class="admin-card-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-800 text-dark m-0">Fitur FAQ & Tanya Jawab</h2>
                <p class="text-muted small">Kelola pertanyaan dari siswa mengenai materi <span class="text-orange fw-700">RPLearn</span></p>
            </div>
            {{-- Statistik mini biar admin tahu beban kerja --}}
            <div class="d-flex gap-2">
                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-700">
                    {{ $questions->where('status', 'pending')->count() }} PENDING
                </span>
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-700">
                    {{ $questions->where('status', 'answered')->count() }} TERJAWAB
                </span>
            </div>
        </div>

        @foreach($questions as $q)
        {{-- Class faq-item-modern otomatis kasih border status di samping --}}
        <div class="faq-item-modern {{ $q->status == 'answered' ? 'faq-status-answered' : 'faq-status-pending' }}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-orange-subtle rounded-circle p-2 text-center" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-circle-question fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 m-0 text-dark">{{ $q->title }}</h5>
                        <small class="text-muted">
                            Oleh: <b class="text-dark">{{ $q->student->name ?? 'Siswa' }}</b>
                            <span class="mx-1">|</span>
                            Modul: <span class="text-orange fw-600">{{ $q->module->title ?? 'Umum' }}</span>
                        </small>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">{{ $q->created_at?->diffForHumans() ?? 'Baru saja' }}</small>
                </div>
            </div>

            <div class="bg-light p-3 rounded-3 mb-4" style="border: 1px dashed #ddd;">
                <p class="text-dark m-0 italic" style="font-size: 0.95rem;">"{{ $q->question }}"</p>
            </div>

            @if($q->status == 'pending')
                {{-- Form Balas Sejajar dengan style input modern --}}
                <form action="{{ route('admin.faq.answer', $q->id) }}" method="POST">
                    @csrf
                    <div class="row g-2 align-items-center">
                        <div class="col-md-10">
                            <textarea name="answer" class="input-modern w-100" rows="2" placeholder="Tulis jawaban resmi admin di sini..." required></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-save-modern w-100 py-3">Balas</button>
                        </div>
                    </div>
                </form>
            @else
                {{-- Tampilan Jawaban Terpasang dengan aksen oranye RPLearn --}}
                <div class="pt-3 border-top mt-2">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa-solid fa-reply text-orange"></i>
                        <label class="small fw-800 text-orange uppercase ls-1">JAWABAN ADMIN</label>
                    </div>
                    <p class="text-secondary m-0 ps-4" style="line-height: 1.6;">{{ $q->answer->answer }}</p>
                </div>
            @endif
        </div>
        @endforeach

        @if($questions->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada pertanyaan masuk hari ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
