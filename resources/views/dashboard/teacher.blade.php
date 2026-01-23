<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Guru</title>
</head>
<body> 
    <h1>Dashboard Guru</h1>

    <p>Halo, {{ auth()->user()->username }}</p>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

    {{-- Flash success popup --}}
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
</body>
</html>
