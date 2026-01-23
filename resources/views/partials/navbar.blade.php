<nav class="navbar">
    <div class="navbar-title">
        <h3>Welcome, {{ auth()->user()->username }}</h3>
    </div>

    <div class="navbar-user">
        <a href="{{ route('profile') }}"><img src="https://ui-avatars.com/api/?name=User" alt="User"></a>
    </div>
</nav>
