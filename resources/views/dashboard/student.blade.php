@extends('layouts.app')

@section('content')

{{-- Flash success popup --}}
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif 
<section class="hero">
    <h1>Start <span>Learning.</span> Keep Growing.</h1>
    <p>RPLearn is designed to support vocational students <br>in developing real-world skills through structured and guided learning.
    </p>

    <div class="features-card">
        <div class="feat-card">
            <i class="ri-book-open-line"></i>
            <span>Learning Modules</span>
        </div>

        <div class="feat-card">
            <i class="ri-bookmark-line"></i>
            <span>Dictionary</span>
        </div>

        <div class="feat-card">
            <i class="ri-question-line"></i>
            <span>FAQ</span>
        </div>
    </div>
</section>

<section class="module-section reveal" id="module-section">
    <h1>Cari <span>Modul</span> Belajarmu!</h1>

    <div class="module-search">
    <i class="ri-search-line"></i>
    <input 
        type="text" 
        placeholder="Search modules..."
    >
</div>

    
    <div class="module-cards">
        <div class="module-card">Modul 1</div>
        <div class="module-card">Modul 2</div>
        <div class="module-card">Modul 3</div>
    </div>
</section>

<section class="faq-section reveal" id="faq-section">
    <h2>F <span>A</span> Q</h2>
    <div class="faq-box">Apa itu RPLearn?</div>
    <div class="faq-box">Bagaimana cara belajar?</div>
</section>

<section class="dictionary-section reveal">
    <h2>Dict<span>io</span>nary</h2>
    <p class="dictionary-desc">
        Learn common technical terms used in vocational learning.
    </p>

    <div class="dictionary-search">
        <i class="ri-search-line"></i>
        <input type="text" id="dictionarySearch" placeholder="Search terms..." />
    </div>

<div class="dictionary-table-wrapper reveal">

    @php
        $grouped = $dictionaries->groupBy(function ($item) {
            return strtoupper(substr($item->term, 0, 1));
        });
    @endphp

    <div class="dictionary-horizontal-wrapper">

        @forelse ($grouped as $letter => $items)

            <div class="dictionary-column dictionary-letter-column">
                <h3 class="dictionary-letter">{{ $letter }}</h3>

                <table class="dictionary-table">
                    <tbody>
                        @foreach ($items as $dictionary)
                            <tr class="dictionary-row">
                                <td>
                                    <span
                                        class="dictionary-term clickable-term"
                                        data-term="{{ $dictionary->term }}"
                                        data-definition="{{ $dictionary->definition }}"
                                    >
                                        {{ $dictionary->term }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @empty
            <p>Data tidak ditemukan.</p>
        @endforelse

    </div>
</div>




</section>



<!-- Dictionary Modal -->
<div class="dictionary-modal" id="dictionaryModal" aria-hidden="true">
    <div class="dictionary-modal-overlay"></div>

    <div class="dictionary-modal-box" role="dialog" aria-modal="true">
        <button class="dictionary-modal-close" id="dictionaryModalClose">
            &times;
        </button>

        <h3 class="dictionary-modal-term" id="dictionaryModalTerm"></h3>
        <p class="dictionary-modal-definition" id="dictionaryModalDefinition"></p>
    </div>
</div>
@endsection


   <script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('dictionaryModal');
    const modalTerm = document.getElementById('dictionaryModalTerm');
    const modalDefinition = document.getElementById('dictionaryModalDefinition');
    const modalClose = document.getElementById('dictionaryModalClose');
    const modalOverlay = document.querySelector('.dictionary-modal-overlay');

    document.querySelectorAll('.clickable-term').forEach(item => {
        item.addEventListener('click', function () {
            modalTerm.textContent = this.dataset.term;
            modalDefinition.textContent = this.dataset.definition;

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    modalClose.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('dictionarySearch');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        // 1️⃣ Filter setiap row
        document.querySelectorAll('.dictionary-row').forEach(row => {
            const term = row
                .querySelector('.dictionary-term')
                .textContent
                .toLowerCase();

            if (term.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // 2️⃣ Sembunyikan kolom huruf kosong
        document.querySelectorAll('.dictionary-letter-column').forEach(column => {
            const visibleRows = column.querySelectorAll(
                '.dictionary-row:not([style*="display: none"])'
            );

            if (visibleRows.length === 0) {
                column.style.display = 'none';
            } else {
                column.style.display = '';
            }
        });
    });

});
</script>

