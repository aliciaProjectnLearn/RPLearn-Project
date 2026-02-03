<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('asset/img/logo.png') }}" alt="RPLearn Logo">
    </div>

    <ul class="sidebar-menu">
        {{-- MENU KHUSUS ADMIN --}}
        @if(auth()->user()->role === 'admin')
            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
            </li>
            <li class="{{ Route::is('admin.modules') ? 'active' : '' }}">
                <a href="{{ route('admin.modules.index') }}">Fitur Modul</a>
            </li>
            <li class="{{ Route::is('admin.dictionaries') ? 'active' : '' }}">
                <a href="{{ route('admin.dictionaries.index') }}">Fitur Kamus</a>
            </li>
            <li class="{{ Route::is('admin.faq') ? 'active' : '' }}">
                <a href="{{ route('admin.faq.index') }}">Fitur FAQ</a>
            </li>
            <li class="{{ Route::is('admin.users') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">Kelola User</a>
            </li>

        {{-- MENU KHUSUS SISWA --}}
        @else
            <li class="{{ Route::is('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ Route::is('student.dashboard') ? '#module-section' : route('student.dashboard').'#module-section' }}">
                    Modules
                </a>
            </li>
            <li>
                <a href="{{ Route::is('student.dashboard') ? '#dictionary-cards' : route('student.dashboard').'#dictionary-cards' }}">
                    Dictionary
                </a>
            </li>
            <li>
                <a href="{{ Route::is('student.dashboard') ? '#faq-section' : route('student.dashboard').'#faq-section' }}">
                    FAQ
                </a>
            </li>
        @endif

        {{-- MENU UMUM (Bisa diakses semua) --}}
        <hr class="sidebar-divider">

        <li class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</aside>
