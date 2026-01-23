<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RPLearn</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

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
</body>
<script src="{{ asset('asset/js/scroll.js') }}"></script>

</html>