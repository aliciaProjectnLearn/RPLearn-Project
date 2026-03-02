<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('asset/img/logo.png') }}" alt="RPLearn Logo">
    </div>

    <ul class="sidebar-menu">
        {{-- MENU KHUSUS ADMIN --}}
        @if(auth()->user()->role === 'admin')
            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="{{ Route::is('admin.modules*') ? 'active' : '' }}">
                <a href="{{ route('admin.modules.index') }}">Fitur Modul</a>
            </li>
            <li class="{{ Route::is('admin.dictionaries*') ? 'active' : '' }}">
                <a href="{{ route('admin.dictionaries.index') }}">Fitur Kamus</a>
            </li>
            <li class="{{ Route::is('admin.faq*') ? 'active' : '' }}">
                <a href="{{ route('admin.faq.index') }}">Ruang Tanya</a>
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
                <a href="{{ route('teacher.faq.index') }}">Fitur Jawaban</a>
            </li>

        {{-- MENU KHUSUS SISWA --}}
        @else
            <li class="{{ Route::is('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
            </li>
            <li class="{{ Route::is('student.modules*') ? 'active' : '' }}">
                <a href="{{ route('student.modules.index') }}">Modul</a>
            </li>
            <li class="{{ Route::is('student.dictionary*') ? 'active' : '' }}">
                <a href="{{ route('student.dictionary.index') }}">Kamus</a>
            </li>
            <li class="{{ Route::is('student.faq*') ? 'active' : '' }}">
                <a href="{{ route('student.faq.index') }}">Ruang Tanya</a>
            </li>
        @endif
    </ul>
</aside>
