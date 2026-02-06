@extends('layouts.app')

@section('content')
<br><br><br>
<div class="content-body px-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Kelola Kamus IT</h2>
            <p class="text-secondary">Total: <span class="text-orange fw-bold">{{ $dictionaries->count() }} Istilah</span></p>
        </div>
        <br>
        <a href="{{ route('admin.dictionaries.create') }}" class="btn-orange">Tambah Istilah</a>
    </div>

    <div class="module-card shadow-sm border-0">
        <div class="table-responsive">
            <table class="rplearn-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4"  style="font-size: 0.8rem">Istilah</th>
                        <th class="text-end pe-4" style="font-size: 0.8rem">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dictionaries as $item)
                    <tr class="module-row">
                        <td class="ps-4" style="text-align: left">
                            <div class="fw-bold text-dark" style="font-weight: bold">{{ $item->term }}</div>
                            <small class="text-muted">{{ Str::limit($item->definition, 50) }}</small>
                        </td>
                        {{-- Aksi --}}
                        <td class="text-end pe-4 action-cell">
                            <div class="action-bar">
                                <a href="{{ route('admin.dictionaries.show', $item->id) }}" title="Detail">
                                    <i class="fa-solid fa-book"></i>
                                </a>


                                <a href="{{ route('admin.dictionaries.edit', $item->id) }}" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.dictionaries.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            onclick="return confirm('Hapus istilah ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
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
