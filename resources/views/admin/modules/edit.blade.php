@extends('layouts.app')

@section('content')
<div class="admin-spacer" style="height: 90px;"></div>

<div class="content-body px-4 pb-5">
    <div class="max-w-900 mx-auto">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
            <div>
                <h3 class="fw-800 text-dark m-0">Edit Modul</h3>
                <p class="text-muted small">Update informasi materi <span class="text-orange fw-bold">RPLearn</span> Anda</p>
            </div>
        </div>
        <br>
        <div class="main-form-card shadow-sm border-0 bg-white rounded-4 p-4" style="border: 1px solid #eef0f2;">
            <form action="{{ route('admin.modules.update', $module->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section mb-5">
                    <h6 class="section-title-premium">
                        <span class="dot-indicator"></span> INFORMASI DASAR
                    </h6>

                    {{-- Class 'row' dan 'col' dibuang supaya input memanjang maksimal --}}
                    <div class="mb-4">
                        <label class="label-modern-bold d-block mb-2">JUDUL MODUL</label>
                        <input type="text" name="title" class="input-modern-premium"
                               style="width: 100%; display: block;" {{-- Paksa panjang 100% --}}
                               value="{{ $module->title }}" placeholder="Masukkan judul materi..." required>
                    </div>
                    <br>
                    <div class="mb-0">
                        <label class="label-modern-bold d-block mb-2">DESKRIPSI MATERI</label>
                        <textarea name="desc" class="input-modern-premium" rows="5"
                                  style="width: 100%; display: block;" {{-- Paksa panjang 100% --}}
                                  placeholder="Jelaskan isi modul ini..." required>{{ $module->desc }}</textarea>
                    </div>
                </div>
                <br>
                <div class="settings-suite-container mb-5 p-1 rounded-4">
                    <div class="bg-white rounded-4 p-4 shadow-sm border">
                        <h6 class="section-title-premium mb-4">
                            <span class="dot-indicator bg-orange"></span> KLASIFIKASI & PENGAJAR
                        </h6>
                        

                        <div class="settings-grid">
                            <div class="setting-item">
                                <div class="setting-icon bg-soft-blue"><i class="fa-solid fa-graduation-cap"></i></div>
                                <div class="setting-content">
                                    <label>KELAS / TINGKATAN</label>
                                    <select name="grade_category_id" class="form-select-premium" required>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ $module->grade_category_id == $grade->id ? 'selected' : '' }}>{{ $grade->grade }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="setting-item">
                                <div class="setting-icon bg-soft-green"><i class="fa-solid fa-book-open"></i></div>
                                <div class="setting-content">
                                    <label>MATA PELAJARAN</label>
                                    <select name="subject_category_id" class="form-select-premium" required>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ $module->subject_category_id == $subject->id ? 'selected' : '' }}>{{ $subject->subject }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="setting-item">
                                <div class="setting-icon bg-soft-purple"><i class="fa-solid fa-code-branch"></i></div>
                                <div class="setting-content">
                                    <label>JALUR BELAJAR (TRACK)</label>
                                    <select name="track" class="form-select-premium" required>
                                        <option value="BE" {{ $module->track == 'BE' ? 'selected' : '' }}>Backend Development</option>
                                        <option value="FE" {{ $module->track == 'FE' ? 'selected' : '' }}>Frontend Development</option>
                                    </select>
                                </div>
                            </div>

                            <div class="setting-item">
                                <div class="setting-icon bg-soft-orange"><i class="fa-solid fa-user-tie"></i></div>
                                <div class="setting-content">
                                    <label>GURU PENGAJAR</label>
                                    <select name="teacher_id" class="form-select-premium" required>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $module->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-footer pt-4 border-top d-flex align-items-center">
                    <button type="submit" class="btn-save-modern border-0"
                            style="background: #f37021; color: white; padding: 12px 35px; border-radius: 12px; font-weight: 700; transition: 0.3s;">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.modules.index') }}" class="btn-cancel-modern ms-4 text-decoration-none fw-bold text-muted small">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
