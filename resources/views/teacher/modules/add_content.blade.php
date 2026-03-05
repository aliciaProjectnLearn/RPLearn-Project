@extends('layouts.app')

@section('content')

<div class="module-content-page content-body px-lg-5 py-5">
    <div class="container-fluid" style="max-width: 1100px;">

        {{-- HEADER --}}
        <div class="mb-4">
            <h3 class="fw-900 text-dark m-0">
                {{ auth()->user()->role === 'teacher' ? 'Review Isi Modul' : 'Kelola Isi Modul' }}
            </h3>
            <p class="text-muted small mb-0">
                Modul: <span class="text-orange fw-bold">{{ $module->title }}</span>
            </p>
        </div>

        {{-- LIST SUBMATERI --}}
        <div class="materi-list-box mb-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="fw-900 m-0">
                        <i class="fa-solid fa-layer-group text-orange me-2"></i>
                        Struktur Materi
                    </h5>
                    <span class="badge-count">
                        {{ $contents->count() }} Sub-Materi
                    </span>
                </div>

                {{-- ✅ Tombol buka modal - pakai onclick sebagai fallback --}}
                @if(auth()->user()->role === 'guru')
                <button
                    class="btn-save-modern"
                    id="btnTambahSubMateri"
                    data-bs-toggle="modal"
                    data-bs-target="#addContentModal"
                    onclick="bukaModal()"
                    type="button">
                    + Tambah Sub-Materi
                </button>
                @endif
            </div>

            <form method="GET" class="mb-3">
                <div class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="input-modern" placeholder="Cari sub-materi...">
                    <button type="submit" class="btn-save-modern">Cari</button>
                </div>
            </form>

            {{-- Scroll Area --}}
            <div class="submateri-scroll">
                @forelse($contents as $index => $content)
                <br>
                    <div class="content-row">
                        <div class="left-info">
                            <div class="hover-number">{{ $index + 1 }}</div>
                            <div>
                                <h6 class="fw-800 mb-1">{{ $content->title }}</h6>
                                <div class="d-flex gap-2">
                                    @if($content->video_url)
                                        <span class="badge-modern bg-soft-blue">VIDEO</span>
                                    @endif
                                    @if($content->file_path)
                                        <span class="badge-modern bg-soft-red">PDF</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="right-action">
                            <a href="{{ route('teacher.modules.content.show', $content->id) }}" class="btn-icon-action" style="text-decoration: none;" title="Lihat Materi">👁</a>

                            @if(auth()->user()->role === 'guru')
                            <form action="{{ route('teacher.modules.content.destroy', $content->id) }}" method="POST" onsubmit="return confirm('Hapus sub-materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon-action danger" title="Hapus Materi">🗑</button>
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-box">
                        Belum ada sub-materi.
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</div>

{{-- ================= MODAL TAMBAH SUB-MATERI ================= --}}
@if(auth()->user()->role === 'guru')
<div class="modal fade text-start" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-900 text-orange" id="addContentModalLabel">Tambah Sub-Materi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutupModal()"></button>
            </div>

            <form action="{{ route('teacher.modules.storeContent', $module->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="label-modern">Judul Materi</label>
                        <input type="text" name="title" class="input-modern" required>
                    </div>

                    <div class="mb-3">
                        <label class="label-modern">Penjelasan</label>
                        <textarea name="content" rows="4" class="input-modern"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="label-modern">Video URL (YouTube)</label>
                        <input type="url" name="video_url" class="input-modern" placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <div class="mb-3">
                        <label class="label-modern">File PDF</label>
                        <input type="file" name="file_path" class="input-modern" accept=".pdf">
                        <small class="text-muted">Max 20MB, PDF only</small>
                    </div>

                    <div class="mb-3">
                        <label class="label-modern">Urutan Materi</label>
                        <input type="number" name="order" class="input-modern" value="{{ $module->contents->count() + 1 }}" min="1">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="tutupModal()" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn-save-modern">Simpan Materi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- STYLE --}}
<style>
.module-content-page { font-family: "Inter", sans-serif; }

.fw-800 { font-weight: 800; font-size: 16px; }
.fw-900 { font-weight: 900; }

.text-orange { color: #f37021 !important; }

.input-modern {
    width: 100%;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.input-modern:focus {
    border-color: #f37021;
    outline: none;
}

.label-modern {
    font-weight: 700;
    margin-bottom: 6px;
    display: block;
}

.btn-save-modern {
    background: #f37021;
    color: white;
    padding: 9px 20px;
    border-radius: 10px;
    border: none;
    font-weight: 800;
}

.materi-list-box {
    background: #f8fafc;
    padding: 25px;
    border-radius: 16px;
}

.badge-count {
    background: white;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 800;
    border: 1px solid #eee;
}

.submateri-scroll {
    max-height: 420px;
    overflow-y: auto;
    padding-right: 5px;
}

.content-row {
    background: white;
    padding: 16px 18px;
    border-radius: 14px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.2s;
}

.content-row:hover {
    border: 1px solid #f37021;
    transform: translateY(-2px);
}

.left-info {
    display: flex;
    gap: 14px;
    align-items: center;
}

.hover-number {
    font-size: 22px;
    font-weight: 900;
    color: #f37021;
}

.badge-modern {
    font-size: 10px;
    font-weight: 800;
    padding: 4px 8px;
    border-radius: 6px;
}

.bg-soft-blue { background: #e0f2fe; color: #0284c7; }
.bg-soft-red { background: #fee2e2; color: #dc2626; }

.right-action {
    display: flex;
    gap: 8px;
}

.btn-icon-action {
    border: none;
    background: #f1f5f9;
    padding: 7px 9px;
    border-radius: 8px;
    cursor: pointer;
}

.btn-icon-action.danger {
    background: #fee2e2;
    color: red;
}

.empty-box {
    padding: 35px;
    text-align: center;
    border: 2px dashed #ddd;
    border-radius: 14px;
}

.addContentModal.show-manual {
    display: block !important;
    background: rgba(0,0,0,0.5);
}
</style>

@push('scripts')
<script>
// ✅ Fungsi fallback jika Bootstrap JS tidak load
function bukaModal() {
    // Coba Bootstrap dulu
    try {
        var modalEl = document.getElementById('addContentModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            // Fallback manual jika bootstrap tidak ada
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.style.background = 'rgba(0,0,0,0.5)';
            document.body.classList.add('modal-open');
        }
    } catch(e) {
        // Fallback paling sederhana
        var modalEl = document.getElementById('addContentModal');
        modalEl.style.display = 'block';
        modalEl.style.background = 'rgba(0,0,0,0.5)';
    }
}

function tutupModal() {
    try {
        var modalEl = document.getElementById('addContentModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();
        } else {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    } catch(e) {
        var modalEl = document.getElementById('addContentModal');
        if (modalEl) modalEl.style.display = 'none';
    }
}

// ✅ Pastikan modal bisa ditutup klik di luar
document.addEventListener('click', function(e) {
    var modal = document.getElementById('addContentModal');
    if (modal && e.target === modal) {
        tutupModal();
    }
});
</script>
@endpush

@endsection
