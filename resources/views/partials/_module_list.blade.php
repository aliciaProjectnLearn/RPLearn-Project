@forelse($modules as $module)
            <div class="module-card">
                <div class="card-header-row">
                    {{-- TEKS KELAS | MATERI WARNA ORANYE --}}
                    <span class="module-meta-text">
                        {{ $module->gradeCategory->grade ?? 'Kelas' }} | {{ $module->subjectCategory->subject ?? 'Materi' }}
                    </span>

                    {{-- IKON MEDIA SEJAJAR HORIZONTAL --}}
                    <div class="media-icons-row">
                        @php
                            $hasVideo = $module->contents->whereNotNull('video_url')->count() > 0;
                            $hasPdf = $module->contents->whereNotNull('file_path')->count() > 0;
                        @endphp
                        @if($hasVideo) <i class="ri-youtube-fill" style="color: #FF0000;"></i> @endif
                        @if($hasPdf) <i class="ri-file-pdf-2-fill" style="color: #f15a24;"></i> @endif
                    </div>
                </div>

                <h3 class="module-title" onclick="showDetail({{ $module->id }})">{{ $module->title }}</h3>

                <div class="author-label">
                    <i class="ri-user-3-line"></i> Oleh: <strong>{{ $module->teacher->name ?? 'Admin' }}</strong>
                </div>

                <p class="module-desc-text">{{ Str::limit($module->desc, 80) }}</p>

                {{-- TOMBOL ORANYE DENGAN HOVER --}}
                <button onclick="showDetail({{ $module->id }})" class="btn-pelajari-orange">
                    Pelajari Sekarang <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: #333; padding: 20px;">Modul tidak ditemukan.</p>
        @endforelse
