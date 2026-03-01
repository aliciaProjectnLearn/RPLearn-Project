@forelse ($modules as $module)
<div class="module-card position-relative">

    {{-- ✅ indikator sudah dibuka (fitur teman kamu) --}}
    @if($module->isViewed)
        <div class="view-badge">
            <i class="ri-check-line"></i>
            Sudah Dibuka
        </div>
    @endif

    {{-- HEADER --}}
    <div class="card-header-row">
        <span class="module-meta-text">
            {{ auth()->user()?->student?->kelas?->nama ?? '-' }} |
            {{ $module->subjectCategory->subject ?? '-' }}
        </span>

        <div class="media-icons-row">
            @if ($module->contents->whereNotNull('video_url')->count())
                <i class="ri-youtube-fill" style="color: var(--orange);"></i>
            @endif
            @if ($module->contents->whereNotNull('file_path')->count())
                <i class="ri-file-pdf-2-fill" style="color: var(--orange);"></i>
            @endif
        </div>
    </div>

    {{-- TITLE --}}
    <h3 class="module-title">
        <a href="{{ route('student.modules.show', $module->id) }}" style="text-decoration: none; color: var(--light);">
            {{ $module->title }}
        </a>
    </h3>


    {{-- DESC --}}
    <p class="module-desc-text">
        {{ Str::limit($module->desc, 80) }}
    </p>

    {{-- FOOTER --}}
    <div class="foot-module-card">

    <a href="{{ route('student.modules.show', $module->id) }}"
    class="btn-pelajari-orange" style="text-decoration: none">
    {{ $module->isViewed ? 'Buka Lagi' : 'Pelajari Sekarang' }}
    </a>

        <div class="author-label">
            <i class="ri-user-3-line" style="color: var(--orange)"></i>
            {{ $module->teacher->username ?? 'Admin' }}
        </div>
    </div>

</div>
@empty
<p style="grid-column: 1 / -1; text-align:center;">
    Modul tidak ditemukan
</p>
@endforelse

<style>
    .module-card{
    position: relative;
}

.view-badge{
    position:absolute;
    top:10px;
    right:10px;
    background:#1da54f;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    font-size:10px;
    font-weight:bold;
}

.foot-module-card{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.btn-pelajari-orange{
    background-color: var(--orange);
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
}
</style>