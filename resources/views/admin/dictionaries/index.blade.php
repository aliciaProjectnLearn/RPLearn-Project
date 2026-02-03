@extends('layouts.app')

@section('content')
<br><br><br>
<div class="content-body px-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Kelola Kamus IT</h2>
            <p class="text-secondary small">Total: <span class="text-orange fw-bold">{{ $dictionaries->count() }} Istilah</span></p>
        </div>
        <br>
        <a href="{{ route('admin.dictionaries.create') }}" class="btn-orange">Tambah Istilah</a>
    </div>

    <div class="module-card shadow-sm border-0">
        <div class="table-responsive">
            <table class="rplearn-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Istilah</th>
                        <th>Modul Terkait</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dictionaries as $item)
                    <tr class="module-row">
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $item->term }}</div>
                            <small class="text-muted">{{ Str::limit($item->definition, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary">{{ $item->module->title ?? 'Umum' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.dictionaries.edit', $item->id) }}" class="text-primary fw-bold text-decoration-none small">Edit</a>
                                <form action="{{ route('admin.dictionaries.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-danger fw-bold border-0 bg-transparent p-0 small" onclick="return confirm('Hapus istilah ini?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
