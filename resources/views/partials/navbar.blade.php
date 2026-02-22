<nav class="navbar">
    <div class="navbar-title">
        <h3>Welcome, <span style="color: var(--orange);">{{ auth()->user()->username }}</span></h3>
    </div>

<div class="navbar-user">
    <a href="{{ route('profile.edit') }}" class="navbar-profile-trigger">
        <span class="d-none d-md-block" style="color: white; font-weight: 600;">{{ Auth::user()->username }}</span>
        <div class="header-profile-avatar">
            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
        </div>
    </a>
</div>
</nav>
