{{-- Looping luar untuk nama jenjang kelas --}}
@forelse($groupedModules as $gradeName => $modulesInGrade)
    <div class="grade-group-container" style="width: 100%; margin-bottom: 40px; grid-column: 1 / -1;">

        {{-- Judul Jenjang Kelas --}}
        <h2 style="font-size: 1.5rem; color: #393E46; border-bottom: 2px solid #F6973F; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="ri-government-line" style="color: #F6973F;"></i> {{ $gradeName }}
        </h2>

        {{-- Container khusus untuk menampung card di dalam jenjang kelas ini --}}
        <div class="module-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">

            {{-- Looping dalam untuk setiap modul di kelas tersebut --}}
            @foreach($modulesInGrade as $module)
                <div class="module-card">
                    <div class="card-header-row">
                        <span class="module-meta-text">
                            {{ $module->gradeCategory->grade ?? 'Kelas' }} | {{ $module->subjectCategory->subject ?? 'Materi' }}
                        </span>

                        <div class="media-icons-row">
                            @php
                                $hasVideo = $module->contents->whereNotNull('video_url')->count() > 0;
                                $hasPdf = $module->contents->whereNotNull('file_path')->count() > 0;
                            @endphp
                            @if($hasVideo) <i class="ri-youtube-fill" style="color: #FF0000;"></i> @endif
                            @if($hasPdf) <i class="ri-file-pdf-2-fill" style="color: #f15a24;"></i> @endif
                        </div>
                    </div>

                    <h3 class="module-title" onclick="showDetail({{ $module->id }})" style="cursor: pointer;">
                        {{ $module->title }}
                    </h3>

                    <div class="author-label">
                        <i class="ri-user-3-line"></i> Oleh: <strong>{{ $module->teacher->name ?? 'Admin' }}</strong>
                    </div>

                    <p class="module-desc-text">{{ Str::limit($module->desc, 80) }}</p>

                    {{-- Ceklis Card 14: Tombol diubah menjadi Lihat detail Modul --}}
                    <button onclick="showDetail({{ $module->id }})" class="btn-pelajari-orange">
                        Lihat detail Modul <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            @endforeach

        </div>
    </div>
@empty
    {{-- Ceklis Card 14: Handling Data tidak ditemukan --}}
    <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
        <i class="ri-file-search-line" style="font-size: 3rem; color: #ccc;"></i>
        <p style="color: #666; font-size: 1.1rem; margin-top: 10px;">Modul tidak ditemukan.</p>
    </div>
@endforelse
