@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 60px;"></div>

<div class="content-body pt-5 px-4">

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert-rplearn mb-4">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header: Rapi & Sejajar --}}
    <div class="page-header d-flex justify-content-between align-items-end mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">Kelola Fitur Modul</h2><br>
            <p class="text-secondary m-0">
                Total: <span class="text-orange fw-bold">{{ $modules->count() }} Modul</span>
            </p>
        </div>
        <br>
        <a href="{{ route('admin.modules.create') }}" class="btn-orange shadow-sm text-decoration-none" style="background: #f37021; color: white; padding: 10px 20px; border-radius: 10px; font-weight: 700;">
            Tambah Modul Baru
        </a>
    </div>

    {{-- Tabel Modul --}}
    <div class="module-card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="rplearn-table align-middle w-100">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Judul Modul</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Mata Pelajaran</th>
                        <th class="text-center py-3 text-uppercase small fw-bold text-muted">Kelas</th>
                        <th class="text-center py-3 text-uppercase small fw-bold text-muted">Pengajar</th>
                        <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modules as $module)
                    <tr class="module-row border-bottom">
                        {{-- Judul & Track --}}
                        <td class="ps-4 py-3" style="text-align: left;">
                            <div class="module-title-text fw-bold text-dark">{{ $module->title }}</div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 12px; letter-spacing: 0.5px;">
                                Track: {{ $module->track ?? 'Umum' }}
                            </small>
                        </td>

                        {{-- Mata Pelajaran--}}
                        <td class="py-3 small fw-600 text-dark" style="text-align: left;">
                            {{ $module->subjectCategory->subject ?? 'Mapel Belum Set' }}
                        </td>

                        {{-- Kelas --}}
                        <td class="text-center py-3">
                            <span class="badge-grade px-3 py-1 rounded-pill bg-light text-dark small fw-bold" style="font-size: 11px;">
                                {{ $module->gradeCategory->grade ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- Pengajar --}}
                        <td class="text-center text-muted small fw-600 py-3">
                            {{ $module->teacher->username ?? 'Admin' }}
                        </td>

                        {{-- Aksi --}}
                        <td class="text-end pe-4 action-cell">
                            <div class="action-bar">
                                <a href="{{ route('admin.modules.addContent', $module->id) }}" title="Isi Materi">
                                    <i class="fa-solid fa-book"></i>
                                </a>

                                <a href="{{ route('admin.modules.edit', $module->id) }}" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.modules.destroy', $module->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            onclick="return confirm('Hapus modul ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted italic small">
                            Belum ada modul yang tersedia di database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
