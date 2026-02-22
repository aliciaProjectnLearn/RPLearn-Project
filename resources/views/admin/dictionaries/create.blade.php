@extends('layouts.app')

@section('content')
<br><br><br>

<div class="content-body px-4">
    <div class="max-w-900 mx-auto">

        {{-- Header Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-800 text-dark m-0" style="font-size: 3rem">{{ isset($dictionary) ? 'Edit' : 'Tambah' }} <span class="text-orange fw-bold">Istilah</span></h3>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="main-form-card">
            <form action="{{ isset($dictionary) ? route('admin.dictionaries.update', $dictionary->id) : route('admin.dictionaries.store') }}" method="POST">
                @csrf
                @if(isset($dictionary)) @method('PUT') @endif

                <div class="form-section mb-5">
                    <h6 class="section-title">DETAIL ISTILAH</h6>

                    {{-- Row 1: Term --}}
                    <div class="row mb-14 align-items-center">
                        <label class="col-sm-3 label-modern" >Istilah (Term)</label><br>
                        <div class="col-sm-9">
                            <input type="text" name="term" class="input-modern" value="{{ $dictionary->term ?? '' }}" placeholder="Contoh: API, Middleware, Database..." required>
                        </div>
                    </div><br>

                    {{-- Row 2: Definition --}}
                    <div class="row mb-4 align-items-start">
                        <label class="col-sm-3 label-modern pt-2">Definisi</label><br>
                        <div class="col-sm-9">
                            <textarea name="definition" class="input-modern" rows="6" placeholder="Jelaskan definisi istilah ini secara mendalam..." required>{{ $dictionary->definition ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-5">
                    <h6 class="section-title">PENGATURAN KONTEKS</h6>

                    {{-- Row 3: Module ID --}}
                    <div class="row align-items-center">
                        <label class="col-sm-3 label-modern">Modul Terkait</label>
                        <div class="col-sm-8">
                            <select name="module_id" class="input-modern appearance-none">
                                <option value="">-- Umum (Tidak Terikat Modul) --</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}" {{ (isset($dictionary) && $dictionary->module_id == $module->id) ? 'selected' : '' }}>
                                        {{ $module->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block small">Pilih modul jika istilah ini spesifik untuk materi tertentu.</small>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-save-modern">
                        {{ isset($dictionary) ? 'Simpan Perubahan' : 'Simpan Istilah' }}
                    </button>
                    <a href="{{ route('admin.dictionaries.index') }}" class="btn-cancel-modern">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
