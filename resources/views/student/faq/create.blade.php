@extends('layouts.app')

@section('content')
    <div class="title">
        <h2>Ajukan <span>Pertanyaan</span></h2>
        <p class="page-subtitle">
            Tanyakan pada guru mengenai kebingunganmu!
        </p>
    </div>
    <div style="max-width:720px; margin:30px auto;">

        <div class="card-create">

            <h2 class="form-title">Tanya <span>Guru</span></h2>
            <p class="form-subtitle">
                Punya pertanyaan tapi belum ada di Ruang Tanya? Kirim langsung ke guru.
            </p>

            <form action="{{ route('student.questions.store') }}" method="POST">
                @csrf

                {{-- Nama Siswa --}}
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" value="{{ auth()->user()->student->name ?? auth()->user()->name }}" disabled>
                </div>

                {{-- Pilih Guru --}}
                <div class="form-group">
                    <label>Guru Tertuju</label>
                    <select name="teacher_id" required>
                        <option value="">Pilih Guru</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Judul --}}
                <div class="form-group">
                    <label>Judul Pertanyaan</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Masalah Login"
                        required>
                    @error('title')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Pertanyaan --}}
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <textarea name="question" rows="5" placeholder="Tuliskan pertanyaanmu secara jelas..." required>{{ old('question') }}</textarea>
                    @error('question')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Kirim Pertanyaan
                </button>

            </form>

        </div>

    </div>


    <style>
        .title h2 {
            font-size: 3rem;
            text-align: center;
            color: var(--dark-soft);
            font-weight: 700;
            margin-top: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .title h2 span {
            color: var(--orange);
        }

        .page-subtitle{
            text-align: center
        }

        .card-create {
            background: linear-gradient(145deg, #111827, #1f2937);
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            color: #f1f5f9;
        }

        .form-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .form-title span {
            color: #f6973f;
        }

        .form-subtitle {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #d1d5db;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 14px;
            border-radius: 10px;
            border: none;
            background: #374151;
            color: #f9fafb;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            box-shadow: 0 0 0 2px #f6973f;
        }

        .form-group input[disabled] {
            background: #4b5563;
            cursor: not-allowed;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #F6973F, #D65A31);
            font-weight: 600;
            font-size: 15px;
            color: white;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(246, 151, 63, 0.4);
        }

        .error-text {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
        }
    </style>
@endsection
