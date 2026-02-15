@extends('layouts.app')

@section('content')
<br><br><br>
<div class="dictionary-detail">

    <a href="{{ route('teacher.dictionaries.index') }}"
       class="dictionary-back">
        ← Kembali
    </a>

    <div class="dictionary-card">
        <h2 class="dictionary-term">
            {{ $dictionary->term }}
        </h2>

        <div class="dictionary-divider"></div>

        <p class="dictionary-definition">
            {{ $dictionary->definition }}
        </p>
    </div>

</div>
@endsection

<style>
    /* =========================
   DETAIL KAMUS (SHOW PAGE)
   ========================= */

.dictionary-detail {
    max-width: 900px;
    margin: 0 auto;
}

.dictionary-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    color: #6c757d;
    text-decoration: none;
    margin-bottom: 16px;
    transition: color 0.2s ease;
}

.dictionary-back:hover {
    color: #ff8c42; /* orange khas RPLearn */
}

.dictionary-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 28px 32px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.dictionary-term {
    font-size: 1.6rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 12px;
}

.dictionary-divider {
    height: 1px;
    background: #e9ecef;
    margin: 16px 0 20px;
}

.dictionary-definition {
    font-size: 0.95rem;
    line-height: 1.8;
    color: #495057;
    white-space: pre-line;
}

/* =========================
   RESPONSIVE
   ========================= */
@media (max-width: 768px) {
    .dictionary-card {
        padding: 22px;
    }

    .dictionary-term {
        font-size: 1.4rem;
    }
}

@media (max-width: 576px) {
    .dictionary-card {
        padding: 18px;
    }

    .dictionary-term {
        font-size: 1.25rem;
    }

    .dictionary-definition {
        font-size: 0.9rem;
    }
}

</style>