<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('asset/img/logo.png') }}" alt="RPLearn Logo">
    </div>

    <ul class="sidebar-menu">
    <li>
        <a href="{{ route('dashboard.student') }}">Dashboard</a>
    </li>
    <li>
        <a href="{{ Route::is('dashboard.student') ? '#module-section' : route('dashboard.student').'#module-section' }}">
            Modules
        </a>
    </li>

    <li>
        <a href="{{ Route::is('dashboard.student') ? '#dictionary-cards' : route('dashboard.student').'#dictionary-cards' }}">
            Dictionary
        </a>
    </li>

    <li>
        <a href="{{ Route::is('dashboard.student') ? '#faq-section' : route('dashboard.student').'#faq-section' }}">
            FAQ
        </a>
    </li>

    <li class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </li>
</ul>

</aside>
