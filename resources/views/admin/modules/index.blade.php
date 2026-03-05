@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 30px;"></div>

<div class="content-body pt-5 px-4">

    {{-- Header --}}
    <div class="page-header d-flex justify-content-between align-items-end mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">Persetujuan Modul (Review)</h2><br>
            <p class="text-secondary m-0">
                Total: <span class="text-orange fw-bold">{{ $modules->count() }} Modul</span>
            </p>
        </div>

        @if($pendingCount > 0)
        <div>
            <button id="btnApproveAll" class="btn text-white fw-bold" style="background: #f37021;">
                <i class="fa-solid fa-check-double me-1"></i>
                Approve All <span class="badge bg-white text-dark ms-1">{{ $pendingCount }}</span>
            </button>
        </div>
        @endif
    </div>
    {{-- Filter Status --}}
    <form method="GET" action="{{ route('admin.modules.index') }}" class="mb-3">
        <div class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari modul berdasarkan judul...">

            <select name="status" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="btn btn-warning text-white fw-bold">Cari</button>
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
                        <th class="text-center py-3 text-uppercase small fw-bold text-muted">Pengajar</th>
                        <th class="text-center py-3 text-uppercase small fw-bold text-muted">Status</th>
                        <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-muted" style="min-width: 130px;">Aksi</th>
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

                        <td class="py-3 small fw-600 text-dark" style="text-align: left;">
                            {{ $module->subjectCategory->subject ?? 'Mapel Belum Set' }}
                        </td>

                        <td class="text-center py-3">
                            <span class="badge-grade px-3 py-1 rounded-pill bg-light text-dark small fw-bold" style="font-size: 11px;">
                                {{ $module->kelas->nama ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="text-center text-muted small fw-600 py-3">
                            {{ $module->teacher->username ?? 'Admin' }}
                        </td>

                        <td class="text-center py-3">
                            @php
                                $status = $module->approval->status ?? 'pending';
                                $badgeColor = match($status) {
                                    'approved' => 'bg-success',
                                    'pending'  => 'bg-warning text-dark',
                                    'revisi'   => 'bg-info text-dark',
                                    'rejected' => 'bg-danger',
                                    default    => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }} text-uppercase px-2 py-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                {{ $status }}
                            </span>

                            @if(!empty($module->approval->comment))
                                <i class="bi bi-chat-left-text" style="cursor: help;" title="Catatan: {{ $module->approval->comment }}"></i>
                            @endif
                        </td>

                        {{-- Aksi Review --}}
                        <td class="text-end pe-4 action-cell">
                            <div class="action-bar d-flex justify-content-end align-items-center gap-2">

                                <a href="{{ route('admin.modules.addContent', $module->id) }}"
                                class="btn btn-sm btn-light border"
                                title="Lihat Isi Materi">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- Tombol Buka Modal Review --}}
                                <button type="button" class="btn btn-sm text-white fw-bold px-3" style="background: #f37021; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $module->id }}">
                                    <i class="bi bi-layout-text-sidebar-reverse"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted italic small">
                            Belum ada modul yang tersedia di database atau tidak ada status yang cocok.
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

{{-- ================= MODAL REVIEW ADMIN DIPISAHKAN KE BAWAH ================= --}}
@foreach ($modules as $module)
    <div class="modal fade text-start" id="reviewModal{{ $module->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                {{-- Form dipindah ke dalam modal-content agar tidak merusak layout Bootstrap --}}
                <form action="{{ route('admin.modules.review', $module->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Review Modul</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Modul yang Direview</label>
                            <input type="text" class="form-control" value="{{ $module->title }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Persetujuan</label>
                            @php
                                $status = $module->approval->status ?? 'pending';
                            @endphp
                            <select name="status" class="form-select" required>
                                <option value="" disabled>-- Pilih Keputusan --</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>✅ Approved (Tampilkan ke Siswa)</option>
                                <option value="revisi" {{ $status == 'revisi' ? 'selected' : '' }}>⚠️ Revisi (Kembalikan ke Guru)</option>
                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>❌ Rejected (Ditolak Total)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Komentar / Alasan</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Tuliskan alasan jika revisi atau rejected...">{{ $module->approval->comment ?? '' }}</textarea>
                            <small class="text-muted">Komentar ini akan dibaca oleh pengajar.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white" style="background: #f37021;">Simpan Keputusan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- ===== SCRIPT APPROVE ALL ===== --}}
@if(isset($pendingCount) && $pendingCount > 0)
<script>
document.getElementById('btnApproveAll')?.addEventListener('click', function () {
    Swal.fire({
        icon: 'warning',
        title: 'Approve Semua Modul?',
        text: 'Semua modul dengan status Pending akan disetujui sekaligus.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve Semua!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f37021',
    }).then((result) => {
        if (!result.isConfirmed) return;

        const btn = document.getElementById('btnApproveAll');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Memproses...';

        fetch('{{ route('admin.modules.approveAll') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => window.location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Terjadi kesalahan!', text: 'Silakan coba lagi.' });
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check-double me-1"></i> Approve All';
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak bisa terhubung ke server.' });
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-double me-1"></i> Approve All';
        });
    });
});
</script>
@endif

@endsection
