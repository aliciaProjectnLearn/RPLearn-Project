@extends('layouts.app')

@section('content')
<br>
<div class="content-body px-4">
    <div class="page-header justify-content-between align-items-center mb-4" style="display: flex; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 1.5rem">
                <h1 class="fw-bold text-dark mb-1">Kelola <span class="text-orange">Kamus</span> IT</h1>
            </div>

            {{-- Filter + Search --}}
            <form method="GET" class="d-flex align-items-center gap-2 flex-wrap" style="margin-left: auto;">

                {{-- Filter kamus --}}
                <div class="kamus-actions">

                    {{-- Search --}}
                    <div class="search-box">
                        <input type="text" id="searchInput" name="search" placeholder="Cari Istilah ..." value="{{ request('search') }}" required>
                        <button type="submit" id="searchBtn" class="btn-search" disabled>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    {{-- Tambah --}}
                    <a href="{{ route('admin.dictionaries.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i> Tambah Istilah
                    </a>
                </div>
            </form>
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
                <tbody id="kamusTable">
                    @foreach($dictionaries as $item)
                    <tr class="module-row kamus-row">
                        <td class="ps-4" style="text-align: left">
                            <div class="fw-bold text-dark kamus-term" style="font-weight: bold">{{ $item->term }}</div>
                            <small class="text-muted kamus-def">{{ Str::limit($item->definition, 50) }}</small>
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

<script>
const searchInput = document.getElementById("searchInput");
const searchBtn   = document.getElementById("searchBtn");
const rows        = document.querySelectorAll(".kamus-row");

searchInput.addEventListener("input", () => {
    const keyword = searchInput.value.toLowerCase().trim();
    searchBtn.disabled = keyword === "";

    rows.forEach(row => {
        const term = row.querySelector(".kamus-term").innerText.toLowerCase();
        const def  = row.querySelector(".kamus-def").innerText.toLowerCase();

        const match = term.includes(keyword) || def.includes(keyword);
        row.style.display = match ? "" : "none";
    });
});
</script>




{{-- Styling --}}
<style>
.card {
    background: rgb(255, 255, 255) !important;
}

.avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff8a00, #ff5e00);
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-action {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    margin-left: 25px;
    justify-content: center;
    font-size: 15px;
    background: #f8f9fa;
    border: 1px solid #eee;
    transition: 0.25s ease;
}

.btn-action:hover {
    background: #ffe8d2;
    border-color: var(--orange);
    transform: translateY(-2px);
}

table th, table td {
    padding: 14px 18px;
    white-space: nowrap;
}
.kamus-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    margin: 15px 0 25px;
}

.kamus-actions select {
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid #ddd;
    font-size: 14px;
    outline: none;
}

.search-box {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 14px;
    overflow: hidden;
    background: white;
}

.search-box input {
    border: none;
    padding: 10px 14px;
    outline: none;
    width: 220px;
    font-size: 14px;
}

.btn-search {
    border: none;
    background: var(--orange);
    color: white;
    padding: 10px 14px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-search:hover {
    opacity: 0.85;
}

.btn-add {
    background: #ff7a00; /* orange */
    color: white;
    padding: 10px 18px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: 0.2s;
}

.btn-add:hover {
    background: #e96c00;
}

</style>

@endsection
