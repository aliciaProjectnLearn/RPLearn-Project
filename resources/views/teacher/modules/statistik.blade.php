@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 30px;"></div>

<div class="content-body pt-5 px-4">

    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">Statistik Modul</h2>
            <p class="text-muted m-0">{{ $module->title }}</p>
        </div>
        <a href="{{ route('teacher.modules.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center border-top border-4 border-success">
                <div class="fw-bold text-muted small text-uppercase mb-1">Total Siswa</div>
                <div class="fw-bold" style="font-size: 2rem;">{{ $allStudents->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center border-top border-4" style="border-color: var(--orange) !important;">
                <div class="fw-bold text-muted small text-uppercase mb-1">Sudah Membuka</div>
                <div class="fw-bold" style="font-size: 2rem; color: var(--orange);">{{ $studentsViewed->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center border-top border-4 border-danger">
                <div class="fw-bold text-muted small text-uppercase mb-1">Belum Membuka</div>
                <div class="fw-bold text-danger" style="font-size: 2rem;">{{ $studentsNotYet->count() }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Siswa yang sudah membuka --}}
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check text-success"></i>
                    <h6 class="fw-bold m-0">Sudah Membuka</h6>
                </div>
                @if($studentsViewed->isEmpty())
                    <div class="text-center text-muted py-5 small">
                        <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                        Belum ada siswa yang membuka modul ini
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($studentsViewed as $student)
                            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                                    style="width: 36px; height: 36px; font-size: 13px; flex-shrink: 0;">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small">{{ $student->name }}</div>
                                    <div class="text-muted" style="font-size: 11px;">NIS: {{ $student->nis }}</div>
                                </div>
                                <span class="badge bg-success ms-auto" style="font-size: 10px;">Sudah</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Siswa yang belum membuka --}}
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                    <h6 class="fw-bold m-0">Belum Membuka</h6>
                </div>
                @if($studentsNotYet->isEmpty())
                    <div class="text-center text-muted py-5 small">
                        <i class="fa-solid fa-party-horn fa-2x mb-2 d-block"></i>
                        Semua siswa sudah membuka modul ini! 🎉
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($studentsNotYet as $student)
                            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold"
                                    style="width: 36px; height: 36px; font-size: 13px; flex-shrink: 0;">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small">{{ $student->name }}</div>
                                    <div class="text-muted" style="font-size: 11px;">NIS: {{ $student->nis }}</div>
                                </div>
                                <span class="badge bg-danger ms-auto" style="font-size: 10px;">Belum</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
