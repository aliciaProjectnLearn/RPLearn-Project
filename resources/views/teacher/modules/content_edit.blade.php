@extends('layouts.app')

@section('content')

<br>

<div class="rg-container">

    <div class="rg-card">

        <h3 class="fw-900 mb-4">✏️ Edit Sub-Materi</h3>

        <br>

        <form action="{{ route('teacher.modules.content.update', $content->id) }}" method="POST">

            @csrf
            @method('PUT')

            <label>Judul</label>
            <input type="text" name="title" value="{{ $content->title }}" class="rg-input">

            <br><br>

            <label>Isi Materi</label>
            <textarea name="content" rows="5" class="rg-input">{{ $content->content }}</textarea>

            <br><br>

            <label>Video URL</label>
            <input type="url" name="video_url" value="{{ $content->video_url }}" class="rg-input">

            <br><br>

            <button class="btn-rg edit">💾 Simpan Update</button>

            <a href="{{ route('teacher.modules.content.show', $content->id) }}" class="btn-rg back">
                Batal
            </a>

        </form>

    </div>
</div>

<br>

<style>

.rg-container {
    max-width: 900px;
    margin: auto;
}

.rg-card {
    background: white;
    padding: 35px;
    border-radius: 22px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}

.rg-input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
}

.rg-input:focus {
    outline: none;
    border-color: orange;
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

.btn-rg.back {
    background: #f82323;
    color: black;
}

.btn-rg.edit {
    background: #fef3c7;
    color: #92400e;
}

.btn-rg:hover {
    transform: translateY(-5px);
}

</style>

@endsection
