@extends('layouts.app')

@section('content')

<br>

<div class="rg-course-page">

    <div class="rg-course-container">

        {{-- SIDEBAR PLAYLIST --}}
        <div class="rg-sidebar">

            <h4 class="rg-sidebar-title">
                📚 Playlist Materi
            </h4>

            <br>

            @foreach($playlist as $item)

                <a href="{{ route('admin.modules.content.show', $item->id) }}"
                   class="rg-playlist-item
                   {{ $item->id == $content->id ? 'active' : '' }}">

                    <span class="rg-playlist-number">
                        {{ $loop->iteration }}
                    </span>

                    <div>
                        <p class="rg-playlist-name">
                            {{ $item->title }}
                        </p>

                        <small class="rg-playlist-meta">
                            @if($item->video_url) 🎥 Video @endif
                            @if($item->file_path) • 📄 PDF @endif
                        </small>
                    </div>

                </a>

                <br>

            @endforeach

        </div>

        {{-- MAIN DETAIL --}}
        <div class="rg-main">

            {{-- HEADER --}}
            <div class="rg-header">

                <div>
                    <h2 class="rg-title">{{ $content->title }}</h2>
                    <p class="rg-subtitle">
                        Detail Sub-Materi
                    </p>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="rg-actions">

                    <a href="{{ route('admin.modules.content.edit', $content->id) }}"
                       class="btn-rg edit">
                        ✏️ Edit
                    </a>

                    <form action="{{ route('admin.modules.content.destroy', $content->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin hapus sub-materi ini bro?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn-rg delete">
                            🗑 Delete
                        </button>

                    </form>

                </div>

            </div>

            <br>

            {{-- CONTENT CARD --}}
            <div class="rg-card">

                <p class="rg-desc">
                    {{ $content->content }}
                </p>

                <br>

                {{-- VIDEO --}}
                @if($content->video_url)
                    <div class="rg-section">
                        <h4>🎥 Video Pembelajaran</h4>
                        <br>

                        <div class="rg-video-box">
                            <iframe
                                src="{{ $content->video_url }}"
                                frameborder="0"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                @endif

                <br>

                {{-- PDF --}}
                @if($content->file_path)
                    <div class="rg-section">
                        <h4>📄 Modul PDF</h4>

                        <br>

                        <a href="{{ asset('storage/'.$content->file_path) }}" target="_blank" class="btn-rg pdf">
                            📥 Buka PDF
                        </a>
                    </div>
                @endif


                {{-- NAVIGATION NEXT PREV --}}
                <div class="rg-nav-buttons">

                    @if($prev)
                        <a href="{{ route('admin.modules.content.show', $prev->id) }}"
                        class="btn-rg nav">
                            ⬅️ Sebelumnya
                        </a>
                    @endif

                    @if($next)
                        <a href="{{ route('admin.modules.content.show', $next->id) }}"
                        class="btn-rg nav next">
                            Berikutnya ➡️
                        </a>
                    @endif

                </div>

                <br>

                {{-- BACK --}}
                <a href="{{ url()->previous('admin.modules.add_content') }}" class="btn-rg back">
                    ⬅ Kembali
                </a>

            </div>

        </div>

    </div>
</div>

<br>

<style>

.rg-course-page {
    background: #cddbe7;
    padding: 40px;
    font-family: "Inter", sans-serif;
    border-radius: 18px;
}

.rg-course-container {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 25px;
    max-width: 1250px;
    margin: auto;
}

/* SIDEBAR */
.rg-sidebar {
    background: white;
    border-radius: 22px;
    padding: 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    height: fit-content;
}

.rg-sidebar-title {
    font-weight: 900;
    font-size: 18px;
}

.rg-playlist-item {
    display: flex;
    gap: 12px;
    padding: 14px;
    border-radius: 16px;
    text-decoration: none;
    color: black;
    transition: 0.2s;
    border: 1px solid transparent;
}

.rg-playlist-item:hover {
    background: #fef3c7;
}

.rg-playlist-item.active {
    background: #f37021;
    color: white;
    font-weight: 800;
}

.rg-playlist-number {
    width: 35px;
    height: 35px;
    border-radius: 12px;
    background: rgba(0,0,0,0.07);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
}

.rg-playlist-item.active .rg-playlist-number {
    background: rgba(255,255,255,0.25);
}

.rg-playlist-name {
    margin: 0;
    font-weight: 800;
}

.rg-playlist-meta {
    font-size: 12px;
    opacity: 0.8;
}

/* MAIN */
.rg-main {
    width: 100%;
}

.rg-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.rg-title {
    font-weight: 900;
    font-size: 26px;
}

.rg-subtitle {
    font-size: 14px;
    color: #000000;
}

.rg-actions {
    display: flex;
    gap: 12px;
}

.btn-rg {
    padding: 10px 18px;
    border-radius: 14px;
    font-weight: 800;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: 0.25s;
}

.btn-rg.edit {
    background: #fef3c7;
    color: #92400e;
}

.btn-rg.delete {
    background: #fee2e2;
    color: #b91c1c;
}

.btn-rg.back {
    background: #f3f4f6;
    color: black;
}

.btn-rg.pdf {
    background: #e0f2fe;
    color: #0369a1;
}

.btn-rg:hover {
    transform: translateY(-2px);
}

/* CARD */
.rg-card {
    background: white;
    padding: 35px;
    border-radius: 22px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}

.rg-desc {
    font-size: 15px;
    line-height: 1.7;
    color: #374151;
}

.rg-video-box {
    border-radius: 18px;
    overflow: hidden;
    border: 2px solid #f3f4f6;
}

.rg-video-box iframe {
    width: 100%;
    height: 420px;
}

.rg-nav-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 35px;
}

.btn-rg.nav {
    background: #f3f4f6;
    color: black;
    padding: 12px 22px;
    border-radius: 16px;
}

.btn-rg.nav.next {
    margin-left: auto;
    background: #f37021;
    color: white;
    font-weight: 900;
}

</style>

@endsection
