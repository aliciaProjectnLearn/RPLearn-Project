<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RPLearn</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
</head>
<body>
    <div class="app-wrapper">
        @include('partials.sidebar')

        <div class="main-content">
            @include('partials.navbar')

            <div class="page-content">
                @yield('content')
            </div>

            @include('partials.footer')
        </div>
    </div>

    {{-- MODAL GLOBAL TARUH SINI --}}
    @stack('modals')

    @stack('scripts')
</body>


{{-- ✅ GLOBAL TOAST NOTIFICATION (CRUD ONLY) --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const Toast = Swal.mixin({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 2600,
        timerProgressBar: true,
        background: "#222831",
        color: "#fff",

        showClass: {
            popup: "swal2-show swal2-slide-in-bottom"
        },
        hideClass: {
            popup: "swal2-hide swal2-slide-out-top"
        },

        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        }
    });

    {{-- CRUD SUCCESS --}}
    @if(session('toast_success'))
        Toast.fire({
            icon: "success",
            title: "{{ session('toast_success') }}"
        });
    @endif

    {{-- CRUD ERROR --}}
    @if(session('toast_error'))
        Toast.fire({
            icon: "error",
            title: "{{ session('toast_error') }}"
        });
    @endif

    {{-- CRUD WARNING --}}
    @if(session('toast_warning'))
        Toast.fire({
            icon: "warning",
            title: "{{ session('toast_warning') }}"
        });
    @endif

});
</script>



<script src="{{ asset('asset/js/scroll.js') }}"></script>

</html>
