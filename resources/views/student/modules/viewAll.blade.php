@extends('layouts.app')

@section('content')

<div class="content-body pt-5 px-4">

    <div class="page-header">
        <h2 class="fw-bold text-dark mb-1" style="text-align: center">Daftar <span style="color: var(--orange)">Modul </span>Belajarmu!</h2>
    </div>

    <form id="moduleFilterForm"
          action="{{ url()->current() }}"
          method="GET"
          class="module-filter-form">

        <div class="module-search">
            <i class="ri-search-line"></i>
            <input type="text"
                   name="search"
                   id="moduleSearchInput"
                   placeholder="Mau belajar apa hari ini?"
                   value="{{ request('search') }}">
        </div>
    </form>

    {{-- CARD --}}
        <div class="module-cards" id="moduleCardsContainer">
            @include('partials._module_list', ['modules' => $modules])
        </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Elemen
    const filterForm = document.getElementById('moduleFilterForm');
    const searchInput = document.getElementById('moduleSearchInput');
    // Pastikan ID container di bawah ini sesuai dengan ID pembungkus list modul kamu di HTML
    const moduleCardsContainer = document.getElementById('moduleCardsContainer'); 

    // 2. Fungsi Utama Fetch
    function fetchModules() {
        // Ambil data dari form
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        
        // Tambahkan indikator loading (opsional tapi bagus untuk UX)
        if (moduleCardsContainer) {
            moduleCardsContainer.style.opacity = '0.5';
        }

        // Kirim request ke URL saat ini dengan parameter search + ajax
        fetch(`${window.location.pathname}?${params}&ajax=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data');
            return response.text();
        })
        .then(html => {
            if (moduleCardsContainer) {
                moduleCardsContainer.innerHTML = html;
                moduleCardsContainer.style.opacity = '1';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (moduleCardsContainer) moduleCardsContainer.style.opacity = '1';
        });
    }

    // 3. Debounce Function (Agar tidak spam request setiap ngetik satu huruf)
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, timeout);
        };
    }

    // 4. Event Listeners
    if (searchInput) {
        // Jalankan fetch saat user mengetik
        searchInput.addEventListener('input', debounce(() => {
            fetchModules();
        }, 300));
    }

    if (filterForm) {
        // Mencegah halaman reload saat user tekan 'Enter'
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            fetchModules();
        });
    }
});
</script>
    
@endpush
@endsection