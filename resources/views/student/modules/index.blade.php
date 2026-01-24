{{-- Extend layout utama --}}
@extends('layouts.app')

@section('content')
<div class="container py-6 mx-auto">
    <h1 class="mb-6 text-2xl font-bold">Modul Pembelajaran</h1>

    {{-- === BAGIAN FILTER & PENCARIAN === --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow">
        <form action="{{ route('student.modules.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            {{-- Filter Pencarian --}}
            <input type="text" name="search" placeholder="Cari judul modul..." value="{{ request('search') }}"
                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

            {{-- Filter Kelas (Grade Category) --}}
            <select name="grade_category_id" class="border-gray-300 rounded-md shadow-sm...">
                <option value="">Semua Kelas</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ request('grade_category_id') == $grade->id ? 'selected' : '' }}>
                        {{ $grade->grade }} </option>
                @endforeach
            </select>

            {{-- Filter Materi (Subject Category) --}}
            <select name="subject_category_id" class="border-gray-300 rounded-md shadow-sm...">
                <option value="">Semua Materi</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_category_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->subject }} </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Terapkan Filter
            </button>
        </form>
    </div>

    {{-- === BAGIAN DAFTAR MODUL (CARDS) === --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($modules as $module)
            {{-- Kartu Modul --}}
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md">
                {{-- (Opsional) Gambar Thumbnail Modul jika ada --}}
                {{-- <img src="..." alt="" class="object-cover w-full h-48"> --}}

                <div class="p-4">
                     {{-- Badges Kategori --}}
                    <div class="flex gap-2 mb-2 text-xs">
                        <span class="px-2 py-1 text-blue-800 bg-blue-100 rounded-full">{{ $module->gradeCategory->grade ?? '-' }}</span>
                        <span class="px-2 py-1 text-green-800 bg-green-100 rounded-full">{{ $module->subjectCategory->subject ?? '-' }}</span>
                    </div>

                    <h2 class="mb-2 text-xl font-semibold">{{ $module->title }}</h2>
                    <p class="mb-4 text-sm text-gray-600 line-clamp-2">{{ $module->desc }}</p>

                    {{-- Tombol Lihat Detail Modul --}}
                    <a href="{{ route('student.modules.show', $module->id) }}" class="inline-block w-full px-4 py-2 text-center text-white bg-indigo-600 rounded hover:bg-indigo-700">
                        Lihat Detail Modul
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <p class="text-center text-gray-500">Tidak ada modul yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Paginasi --}}
    <div class="mt-6">
        {{ $modules->links() }}
    </div>
</div>
@endsection
