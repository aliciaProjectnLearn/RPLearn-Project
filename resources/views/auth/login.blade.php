<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | RPLearn</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

            {{-- <h2 class="login-title"></h2> --}}

            {{-- Username --}}
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input
                    type="text"
                    name="username"
                    placeholder="Username"
                    value="{{ old('username') }}"
                    required
                >
            </div>

            {{-- Password --}}
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                >
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>
        </form>

    </div>

</div>

{{-- 🔔 ALERT --}}
@if (session('success'))
<script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->has('username'))
<script>alert("{{ $errors->first('username') }}");</script>
@endif

</body>
</html>
