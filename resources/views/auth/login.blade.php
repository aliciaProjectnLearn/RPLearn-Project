<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | RPLearn</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icons/pplg-logo.ico') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

</head>

<body>

    <div class="login-wrapper">

        <div class="login-card">

            {{-- LOGO --}}
            <div class="login-logo">
                <img src="{{ asset('images/pplg-logo.png') }}" alt="Logo">
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Username --}}
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                </div>

                {{-- Password --}}
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>
            </form>

        </div>

    </div>

    {{-- 🔔 LOGOUT SUCCESS ALERT --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                background: '#393E46',
                color: '#ffffff',
                backdrop: `
        rgba(0,0,0,0.4)
        url("{{ asset('images/nyan-cat.gif') }}")
        left top
        no-repeat
    `
            });
        </script>
    @endif

    <style>
        .swal2-timer-progress-bar {
            background: #F6973F !important;
        }
    </style>


</body>

</html>
