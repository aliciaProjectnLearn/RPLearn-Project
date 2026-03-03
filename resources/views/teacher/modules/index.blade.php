@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 30px;"></div>

<div class="content-body pt-5 px-4">

    {{-- Header --}}
    <div class="page-header d-flex justify-content-between align-items-end mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">Modul Saya</h2><br>
            <p class="text-secondary m-0">
                Total: <span class="text-orange fw-bold">{{ $modules->count() }} Modul</span>
            </p>
        </div>
        <br>
        <a href="{{ route('teacher.modules.create') }}" class="shadow-sm text-decoration-none" style="background: var(--orange); color: white; padding: 10px 20px; border-radius: 10px; font-weight: 700;">
            Tambah Modul Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #198754;">
            <i class="ri-checkbox-circle-line"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #dc3545;">
            <i class="ri-close-circle-line"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2 mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #dc3545;">
            <i class="ri-error-warning-line mt-1"></i>
            <div>
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li style="font-size: 13px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('teacher.modules.index') }}" class="mb-3">
        <div class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari modul berdasarkan judul...">

            <select name="status" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="btn text-white fw-bold" style="background-color: var(--orange)">Cari</button>
        </div>
    </form>

    {{-- Tabel Modul --}}
    <div class="module-card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="rplearn-table align-middle w-100">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Judul Modul</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Mata Pelajaran</th>
                        <th class="text-center py-3 text-uppercase small fw-bold text-muted">Kelas</th>
                        <th class="text-center py-3 text-uppercase small fw-bold text-muted">Status</th>
                        <th class="text-center pe-4 py-3 text-uppercase small fw-bold text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modules as $module)
                    <tr class="module-row border-bottom">
                        <td class="ps-4 py-3" style="text-align: left;">
                            <div class="module-title-text fw-bold text-dark">{{ $module->title }}</div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 12px; letter-spacing: 0.5px;">
                                Track: {{ $module->track ?? 'Umum' }}
                            </small>
                        </td>

                        <td class="py-3 small fw-600 text-dark" style="text-align: left;">
                            {{ $module->subjectCategory->subject ?? 'Mapel Belum Set' }}
                        </td>

                        <td class="text-center py-3">
                            @if($module->kelas)
                                <span class="px-3 py-1 rounded-pill small fw-bold" style="font-size: 11px; background-color: #fff3e0; color: var(--orange, #f57c00); border: 1px solid #ffcc80;">
                                    <i class="ri-group-line" style="font-size: 10px;"></i>
                                    {{ $module->kelas->nama }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <td class="text-center py-3">
                            @php
                                $status = $module->approval->status ?? 'pending';
                                $badgeColor = match($status) {
                                    'approved' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'revisi' => 'bg-info text-dark',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }} text-uppercase px-2 py-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                {{ $status }}
                            </span>

                            @if(!empty($module->approval->comment))
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#noteModal{{ $module->id }}" style="font-size: 11px; padding: 2px 8px; border-radius: 6px;">
                                        <i class="ri-mail-open-line"></i> Lihat Catatan
                                    </button>
                                </div>
                            @endif
                        </td>

                        <td class="text-end pe-4 action-cell">
                            <div class="action-bar d-flex justify-content-end align-items-center gap-3">

                                {{-- ✅ Tombol Statistik --}}
                                <a href="{{ route('teacher.modules.statistik', $module->id) }}" title="Lihat Statistik">
                                    <i class="ri-bar-chart-line text-info"></i>
                                </a>

                                {{-- Tombol Kelola Sub-Materi --}}
                                <a href="{{ route('teacher.modules.addContent', $module->id) }}" class="position-relative" title="Kelola Sub-Materi">
                                    <i class="ri-stack-line text-primary"></i>
                                    @if($module->contents->count())
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 10px;">
                                            {{ $module->contents->count() }}
                                        </span>
                                    @endif
                                </a>

                                {{-- Tombol Edit Modul --}}
                                <a href="{{ route('teacher.modules.edit', $module->id) }}" title="Edit">
                                    <i class="ri-pencil-line text-warning"></i>
                                </a>

                                {{-- Tombol Hapus Modul --}}
                                <form action="{{ route('teacher.modules.destroy', $module->id) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus" onclick="return confirm('Hapus modul ini?')" style="background: none; border: none; padding: 0;">
                                        <i class="ri-delete-bin-line text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted italic small">
                            Kamu belum membuat modul atau tidak ada status yang cocok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $modules->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Modal Catatan Admin --}}
@foreach ($modules as $module)
    @if(!empty($module->approval->comment))
    <div class="modal fade text-start" id="noteModal{{ $module->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-info">
                        <i class="ri-clipboard-line"></i> Catatan Revisi / Penolakan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($module->kelas)
                        <div class="mb-3 p-2 rounded" style="background-color: #fff3e0; border-left: 3px solid var(--orange, #f57c00);">
                            <small class="fw-bold text-muted text-uppercase" style="font-size: 11px;">Kelas</small>
                            <div class="fw-bold" style="color: var(--orange, #f57c00);">
                                <i class="ri-group-line" style="font-size: 12px;"></i>
                                {{ $module->kelas->nama }}
                            </div>
                        </div>
                    @endif
                    <p class="text-dark" style="white-space: pre-line;">
                        {{ $module->approval->comment }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection
