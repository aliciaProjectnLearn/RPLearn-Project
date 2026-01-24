@extends('layouts.app')

@section('content')
<div class="container py-6 mx-auto">
    {{-- Tombol Kembali --}}
    <a href="{{ route('student.modules.index') }}" class="mb-4 text-indigo-600 hover:underline">&larr; Kembali ke Daftar Modul</a>

    <div class="p-6 bg-white rounded-lg shadow">
        {{-- Header Modul --}}
        <h1 class="mb-2 text-3xl font-bold">{{ $module->title }}</h1>
        <div class="flex items-center gap-4 mb-6 text-sm text-gray-500">
            <span>Oleh: {{ $module->teacher->name ?? 'Guru' }}</span> |
            <span>Kelas: {{ $module->gradeCategory->grade ?? '-' }}</span> |
            <span>Materi: {{ $module->subjectCategory->subject ?? '-' }}</span>
        </div>

        {{-- Deskripsi Modul --}}
        <div class="mb-8 prose max-w-none">
            <h3 class="text-lg font-semibold">Deskripsi:</h3>
            <p>{{ $module->desc }}</p>
        </div>

        <hr class="my-6">

        {{-- === BAGIAN KONTEN MODUL (Daftar Isi/Materi) === --}}
        <h2 class="mb-4 text-2xl font-bold">Materi Pembelajaran</h2>

        <div class="space-y-4">
            @forelse($module->contents as $index => $content)
                <div class="p-4 border rounded-lg">
                    <h3 class="mb-2 text-lg font-semibold">
                        Bagian {{ $index + 1 }}: {{ $content->title }}
                    </h3>
                    {{-- Menampilkan isi konten. Gunakan {!! !!} HANYA jika kamu menyimpan HTML di database dan sudah mesanitasi inputnya (misal pakai Summernote/CKEditor) --}}
                    <div class="prose max-w-none">
                        {!! $content->content !!}
                    </div>
                    {{-- Jika ada file upload/link, tambahkan tombol download/akses di sini --}}
                </div>
            @empty
                <p class="text-gray-500">Belum ada konten materi untuk modul ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
