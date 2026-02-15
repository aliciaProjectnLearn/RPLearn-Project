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
            <li class="{{ Route::is('admin.modules*') ? 'active' : '' }}">
                <a href="{{ route('admin.modules.index') }}">Fitur Modul</a>
            </li>
            <li class="{{ Route::is('admin.dictionaries*') ? 'active' : '' }}">
                <a href="{{ route('admin.dictionaries.index') }}">Fitur Kamus</a>
            </li>
            <li class="{{ Route::is('admin.faq*') ? 'active' : '' }}">
                <a href="{{ route('admin.faq.index') }}">Fitur FAQ</a>
            </li>
            <li class="{{ Route::is('admin.users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index', ['role'=>'siswa']) }}">Kelola User</a>
            </li>

        {{-- MENU KHUSUS GURU --}}
        @elseif(auth()->user()->role === 'guru')
            <li class="{{ Route::is('teacher.dashboard') ? 'active' : '' }}">
                <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
            </li>
            <li class="{{ Route::is('teacher.modules*') ? 'active' : '' }}">
                <a href="{{ route('teacher.modules.index') }}">Fitur Modul</a>
            </li>
            <li class="{{ Route::is('teacher.dictionaries*') ? 'active' : '' }}">
                <a href="{{ route('teacher.dictionaries.index') }}">Fitur Kamus</a>
            </li>
            <li class="{{ Route::is('teacher.faq*') ? 'active' : '' }}">
                <a href="{{ route('teacher.faq.index') }}">Fitur Q&A</a>
            </li>

        {{-- MENU KHUSUS SISWA --}}
        @else
            <li class="{{ Route::is('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
            </li>
            <li class="{{ Route::is('student.modules*') ? 'active' : '' }}">
                <a href="{{ route('student.modules.index') }}">Modules</a>
            </li>
            <li class="{{ Route::is('student.dictionary*') ? 'active' : '' }}">
                <a href="{{ route('student.dictionary.index') }}">Dictionaries</a>
            </li>

            <li>
                <a href="{{ Route::is('student.dashboard') ? '#faq-section' : route('student.dashboard').'#faq-section' }}" class="sidebar-link">
                    FAQ
                </a>
            </li>
            {{-- <li class="{{ Route::is('student.saveModul*') ? 'active' : '' }}">
                <a href="{{ route('student.saveModul.index') }}">Modul Disimpan</a>
            </li> --}}
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

<style>
    /* ===============================
   SIDEBAR ACTIVE + HOVER EFFECT
   =============================== */

.sidebar .sidebar-menu li a {
    display: block;
    padding: 12px 18px;
    border-radius: 12px;
    font-weight: 600;
    color: #cbd5e1;
    transition: 0.25s ease;
}

/* Hover nyala */
.sidebar .sidebar-menu li a:hover {
    background: rgba(243, 112, 33, 0.15);
    color: #f37021;
    transform: translateX(4px);
}

/* Active menu nyala terus */
.sidebar .sidebar-menu li.active a {
    background: rgba(243, 112, 33, 0.22);
    color: #f37021 !important;
    font-weight: 700;
    position: relative;
}

/* Garis indikator kiri */
.sidebar .sidebar-menu li.active a::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 5px;
    height: 70%;
    border-radius: 10px;
    background: #f37021;
}

.sidebar .sidebar-menu li.active a {
    position: relative;
}


</style>
