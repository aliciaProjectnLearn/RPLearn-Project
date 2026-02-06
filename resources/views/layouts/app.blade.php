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

    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
</head>
<body>
    <div class="app-wrapper">
        {{-- sidebar --}}
        @include('partials.sidebar')

        <div class="main-content">
            {{-- navbar --}}
            @include('partials.navbar')

            {{-- content --}}
            <div class="page-content">
                @yield('content')
            </div>

            {{-- footer --}}
            @include('partials.footer')
        </div>
    </div>
        <!-- Modal Detail Kamus -->
    <div class="modal fade" id="dictionaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTerm"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p id="modalDefinition" class="text-secondary"></p>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="{{ asset('asset/js/scroll.js') }}"></script>

</html>
