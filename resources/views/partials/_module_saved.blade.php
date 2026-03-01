@forelse ($modules as $module)
<div class="module-card">

    {{-- HEADER --}}
    <div class="card-header-row">

        <div class="left-meta">
            {{-- badge view --}}
            @if($module->isViewed)
                <span class="view-badge">
                    <i class="ri-check-line"></i> Sudah Dibuka
                </span>
            @endif

            <span class="module-meta-text">
                {{ auth()->user()?->student?->kelas?->nama ?? '-' }} |
                {{ $module->subjectCategory->subject ?? '-' }}
            </span>
        </div>

        {{-- tombol save --}}
        <div class="media-icons-row">
            <i class="{{ $module->isSaved ? 'ri-bookmark-fill saved' : 'ri-bookmark-line' }} save-btn"
               data-id="{{ $module->id }}">
            </i>
        </div>

    </div>

    {{-- TITLE --}}
    <h3 class="module-title">
        <a href="{{ route('student.modules.show', $module->id) }}">
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
           class="btn-pelajari-orange">
           {{ $module->isViewed ? 'Buka Lagi' : 'Pelajari Sekarang' }}
        </a>

        <div class="author-label">
            <i class="ri-user-3-line"></i>
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

.card-header-row{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.left-meta{
    display:flex;
    flex-direction:column;
    gap:4px;
}

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

.save-btn{
    font-size:20px;
    cursor:pointer;
    color:#f57c00;
}

.save-btn.saved{
    color:#f57c00;
}

.module-title a{
    text-decoration:none;
    color:var(--light);
}


.btn-pelajari-orange{
    background:linear-gradient(135deg,#F6973F,#D65A31);
    color:white;
    padding:8px 12px;
    border-radius:6px;
    font-size:12px;
    text-decoration: none;
}
</style>