@extends('layouts.app')

@section('content')
{{-- Jarak sakti lo biar gak nyelam ke bawah navbar --}}
<br><br><br>

<div class="content-body px-4">
    <div class="max-w-900 mx-auto"> {{-- Biar fokus di tengah --}}

        {{-- Header Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-800 text-dark m-0">Tambah Modul Baru</h3>
                <p class="text-muted small">Buat materi pembelajaran baru untuk <span class="text-orange fw-bold">RPLearn</span></p>
            </div>
            <a href="{{ route('admin.modules.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        {{-- Container Utama (Card) --}}
        <div class="main-form-card">
            <form action="{{ route('admin.modules.store') }}" method="POST">
                @csrf

                <div class="form-section mb-5">
                    <h6 class="section-title">INFORMASI DASAR</h6>

                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Judul Modul</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="input-modern" placeholder="Contoh: Dasar Pemrograman Laravel" required>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-start">
                        <label class="col-sm-3 label-modern pt-2">Deskripsi Singkat</label>
                        <div class="col-sm-9">
                            <textarea name="desc" class="input-modern" rows="4" placeholder="Jelaskan isi modul ini secara ringkas..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-5">
                    <h6 class="section-title">KLASIFIKASI & PENGAJAR</h6>

                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Kelas (Grade)</label>
                        <div class="col-sm-6">
                        <select name="grade_category_id" class="input-modern" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->grade }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Mata Pelajaran</label>
                        <div class="col-sm-6">
                        <select name="subject_category_id" class="input-modern" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-3 label-modern">Jalur (Track)</label>
                        <div class="col-sm-6">
                            <select name="track" class="input-modern" required>
                                <option value="">-- Pilih Track --</option>
                                <option value="BE">Backend Development</option>
                                <option value="FE">Frontend Development</option>
                            </select>
                        </div>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 label-modern">Pengajar (Guru)</label>
                            <div class="col-sm-6">
                                <select name="teacher_id" class="input-modern" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'guru')
                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 label-modern">Kelas yang Diajar</label>
                            <div class="col-sm-6">
                                <select name="kelas_id" class="input-modern" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <small class="text-danger">{{ $message }}</small>
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
                    <a href="{{ route('admin.modules.index') }}" class="btn-cancel-modern">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
