@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 30px;"></div>

<div class="content-body pt-5 px-4">

    <div class="page-header d-flex justify-content-between align-items-end mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">Modul Pembelajaran</h2>
            <p class="text-secondary m-0">
                Menampilkan modul untuk kelasmu:
                <span class="text-orange fw-bold">{{ $modules->count() }} Modul</span>
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('student.modules.index') }}" class="mb-4">
        <div class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="max-width: 300px;" placeholder="Cari judul modul...">

            <select name="subject_id" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->subject }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn text-white fw-bold" style="background-color: var(--orange)">Cari</button>
        </div>
    </form>

    {{-- Module Cards --}}
    @if($modules->isEmpty())
        {{-- ✅ Empty state --}}
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-box-open fa-3x mb-3 d-block"></i>
            <h5 class="fw-bold">Belum ada modul untuk kelasmu</h5>
            <p class="small">Guru belum mengupload modul untuk kelasmu. Coba lagi nanti!</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($modules as $module)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden position-relative">

                        {{-- ✅ Indikator sudah dibuka --}}
                        @if($module->isViewed)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success" style="font-size: 10px;">
                                    <i class="fa-solid fa-circle-check me-1"></i>Sudah Dibuka
                                </span>
                            </div>
                        @endif

                        <div class="card-body p-4">
                            {{-- Label Kelas & Mapel --}}
                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                @if($module->kelas)
                                    <span class="badge rounded-pill" style="font-size: 10px; background-color: #fff3e0; color: var(--orange, #f57c00); border: 1px solid #ffcc80;">
                                        <i class="fa-solid fa-users"></i> {{ $module->kelas->nama }}
                                    </span>
                                @endif
                                <span class="badge rounded-pill bg-light text-dark border" style="font-size: 10px;">
                                    {{ $module->subjectCategory->subject ?? '-' }}
                                </span>
                                <span class="badge rounded-pill bg-light text-dark border" style="font-size: 10px;">
                                    Track: {{ $module->track ?? '-' }}
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2">{{ $module->title }}</h5>
                            <p class="text-muted small mb-4" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $module->desc }}
                            </p>

                            <a href="{{ route('student.modules.show', $module->id) }}"
                                class="btn w-100 fw-bold text-white"
                                style="background: var(--orange); border-radius: 8px;">
                                {{ $module->isViewed ? 'Buka Lagi' : 'Lihat Modul' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
