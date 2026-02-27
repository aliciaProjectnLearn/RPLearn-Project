@forelse ($modules as $module)
    <div class="module-card">
        <div class="card-header-row">
            <span class="module-meta-text">
                {{ $module->gradeCategory->grade ?? '-' }} |
                {{ $module->subjectCategory->subject ?? '-' }}
            </span>

            <div class="media-icons-row">
                <i class="ri-bookmark-line save-btn saved"
                       data-id="{{ $module->id }}">
                </i>
            </div>
        </div>

        <h3 class="module-title"
            onclick="showDetail({{ $module->id }})"
            style="padding-bottom: 10px; padding-top: 10px;">
            {{ $module->title }}
        </h3>

        <p class="module-desc-text">
            {{ Str::limit($module->desc, 80) }}
        </p>

        <br>

        <div class="foot-module-card" style="gap: 1rem">
            <button class="btn-pelajari-orange"
                onclick="showDetail({{ $module->id }})"
                style="background: linear-gradient(135deg, #F6973F, #D65A31); font-size: 0.7rem;">
                Pelajari Sekarang
            </button>

            <div class="author-label"
                style="font-size: 0.7rem; color: var(--light);">
                <i class="ri-user-3-line"
                    style="margin-right: 5px; color: var(--orange);"></i>
                {{ $module->teacher->username ?? 'Admin' }}
            </div>
        </div>
    </div>
@empty
    <p style="grid-column: 1 / -1; text-align:center;">
        Modul tidak ditemukan
    </p>
@endforelse