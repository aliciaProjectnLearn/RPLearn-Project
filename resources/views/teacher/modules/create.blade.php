@extends('layouts.app')

@section('content')
    <br><br><br>

    <div class="content-body px-4">
        <div class="max-w-900 mx-auto">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-800 text-dark m-0">Tambah Modul Baru</h3>
                    <p class="text-muted small">Buat materi pembelajaran baru untuk <span class="text-orange fw-bold">RPLearn</span></p>
                </div>
                <a href="{{ route('teacher.modules.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #198754;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #dc3545;">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2 mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #dc3545;">
                    <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                    <div>
                        <strong>Gagal menyimpan modul!</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li style="font-size: 13px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="main-form-card">
                <form action="{{ route('teacher.modules.store') }}" method="POST">
                    @csrf

                    <div class="form-section mb-5">
                        <h6 class="section-title">INFORMASI DASAR</h6>

                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 label-modern">Judul Modul</label>
                            <div class="col-sm-9">
                                <input type="text" name="title" class="input-modern" placeholder="Contoh: Dasar Pemrograman Laravel" value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div class="row mb-4 align-items-start">
                            <label class="col-sm-3 label-modern pt-2">Deskripsi Singkat</label>
                            <div class="col-sm-9">
                                <textarea name="desc" class="input-modern" rows="4" placeholder="Jelaskan isi modul ini secara ringkas..." required>{{ old('desc') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section mb-5">
                        <h6 class="section-title">KLASIFIKASI & PENGAJAR</h6>

                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 label-modern">Mata Pelajaran</label>
                            <div class="col-sm-6">
                                <select name="subject_category_id" class="input-modern" required>
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_category_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 label-modern">Jalur (Track)</label>
                            <div class="col-sm-6">
                                <select name="track" class="input-modern" required>
                                    <option value="">-- Pilih Track --</option>
                                    <option value="BE" {{ old('track') == 'BE' ? 'selected' : '' }}>Backend Development</option>
                                    <option value="FE" {{ old('track') == 'FE' ? 'selected' : '' }}>Frontend Development</option>
                                </select>
                            </div>
                        </div>

                        @if (auth()->user()->role === 'teacher')
                            <div class="row mb-4 align-items-center">
                                <label class="col-sm-3 label-modern">Pengajar (Guru)</label>
                                <div class="col-sm-6">
                                    <select name="teacher_id" class="input-modern" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if (auth()->user()->role === 'guru')
                            <div class="row mb-4 align-items-start">
                                <label class="col-sm-3 label-modern pt-2">Kelas yang Diajar</label>
                                <div class="col-sm-6">
                                    @if ($kelas->isNotEmpty())
                                        {{-- Kotak checkbox mirip admin --}}
                                        <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 12px 16px; background: #fff; max-height: 220px; overflow-y: auto;">
                                            @foreach ($kelas as $k)
                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="kelas_ids[]"
                                                        value="{{ $k->id }}"
                                                        id="kelas_{{ $k->id }}"
                                                        {{ is_array(old('kelas_ids')) && in_array($k->id, old('kelas_ids')) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="kelas_{{ $k->id }}">
                                                        {{ $k->nama }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fa-solid fa-circle-info" style="font-size: 11px;"></i>
                                            Centang semua kelas yang akan menerima modul ini
                                        </small>
                                    @else
                                        <input type="text" class="input-modern" value="Belum ada kelas terdaftar" disabled style="background-color: #f0f0f0; cursor: not-allowed; color: #dc3545;">
                                        <small class="text-danger mt-1 d-block">
                                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 11px;"></i>
                                            Hubungi teacher untuk mendaftarkan kelas Anda
                                        </small>
                                    @endif

                                    @error('kelas_ids')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="row align-items-center">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1">
                                    <label class="form-check-label small text-muted fw-bold" for="is_published">
                                        TERBITKAN MODUL SEKARANG
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn-save-modern">
                            Simpan Modul
                        </button>
                        <a href="{{ route('teacher.modules.index') }}" class="btn-cancel-modern">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
