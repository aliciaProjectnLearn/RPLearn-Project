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
/* GRID WRAPPER (kalau belum ada) */
.modules-grid{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(280px,1fr));
    gap:25px;
}

/* CARD */
.module-card{
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    background:#1f2937;
    border-radius:18px;
    padding:20px;
    height:100%;              /* penting */
}

/* BAGIAN TENGAH fleksibel */
.module-card .module-desc-text{
    flex-grow:1;              /* ini bikin tinggi rata */
}

/* FOOTER selalu di bawah */
.foot-module-card{
    margin-top:auto;          /* ini kunci utamanya */
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* Badge tetap aman */
.view-badge{
    display:inline-flex;
    align-items:center;
    gap:4px;
    background:#16a34a;
    color:white;
    padding:2px 8px;
    border-radius:20px;
    font-size:10px;
    font-weight:bold;
    width:fit-content;
    margin-bottom:10px;
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