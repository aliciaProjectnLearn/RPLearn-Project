<nav class="navbar">
    <div class="navbar-title">
        <h3 style="font-size: 1rem;">Welcome, <span style="color: var(--orange);">{{ auth()->user()->username }}</span>
        </h3>
    </div>

    <div class="navbar-user" style="position: relative; margin-right: 3rem;">

        <div onclick="toggleProfileMenu()"
            style="cursor: pointer; display: flex; align-items: center; gap: 15px; user-select: none;">
            <span style="color: white; font-weight: 600; font-size: 0.8rem;">
                {{ Auth::user()->username }} </i>
            </span>

            <div
                style="width: 35px; height: 35px; background-color: #F6973F; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: 700; font-size: 1rem; box-shadow: 0 4px 10px rgba(246, 151, 63, 0.3);">
                {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
            </div>
        </div>

        <div id="profileDropdown"
    style="display:none; position:absolute; top:60px; right:0; background:white; min-width:220px; border-radius:12px; box-shadow:0 15px 35px rgba(0,0,0,0.15); overflow:hidden; z-index:9999; border:1px solid #eee;">

    <div style="padding:16px 20px; background:#f8f9fa; border-bottom:1px solid #eee;">
        <p style="margin:0; font-size:0.8rem; color:#888;">Masuk sebagai</p>
        <p style="margin:0; font-weight:bold; color:#222831; font-size:0.8rem;">
            {{ Auth::user()->username }}
        </p>
    </div>

    {{-- PROFILE --}}
    <a href="{{ route('profile.edit') }}"
        style="display:block; padding:14px 20px; color:#393E46; text-decoration:none; font-size:0.7rem; font-weight:500;"
        onmouseover="this.style.background='#f1f1f1'" onmouseout="this.style.background='white'">
        <i class="ri-user-settings-line" style="width:25px; color:#F6973F; margin-right:10px;"></i>
        Pengaturan Profil
    </a>

    @auth
        @if(auth()->user()->role === 'siswa')

            {{-- MODUL --}}
            <a href="{{ route('student.modules.saved') }}"
                style="display:block; padding:14px 20px; color:#393E46; text-decoration:none; font-size:0.7rem; font-weight:500;"
                onmouseover="this.style.background='#f1f1f1'" onmouseout="this.style.background='white'">
                <i class="ri-bookmark-line" style="width:25px; color:#F6973F; margin-right:10px;"></i>
                Modul Tersimpan
            </a>

            {{-- PERTANYAAN --}}
            <a href="{{ route('student.questions.index') }}"
                style="display:block; padding:14px 20px; color:#393E46; text-decoration:none; font-size:0.7rem; font-weight:500;"
                onmouseover="this.style.background='#f1f1f1'" onmouseout="this.style.background='white'">
                <i class="ri-question-line" style="width:25px; color:#F6973F; margin-right:10px;"></i>
                Pertanyaanmu
            </a>

        @endif
    @endauth

    <form method="POST" action="{{ route('logout') }}" style="margin:0; border-top:1px solid #eee;">
        @csrf
        <button type="submit"
            style="display:block; width:100%; text-align:left; background:white; border:none; padding:14px 20px; color:#d63031; font-size:0.7rem; font-weight:600; cursor:pointer;">
            <i class="ri-logout-box-line" style="width:25px; margin-right:10px;"></i>
            Keluar
        </button>
    </form>

</div>
    </div>
</nav>

<script>
    function toggleProfileMenu() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Menutup dropdown jika user mengklik area kosong di luar dropdown
    window.addEventListener('click', function(event) {
        const navbarUser = document.querySelector('.navbar-user');
        const dropdown = document.getElementById('profileDropdown');

        // Cek apakah klik terjadi di luar div .navbar-user
        if (navbarUser && !navbarUser.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    document.getElementById('logout-btn').addEventListener('click', function(e) {
        e.preventDefault();

        const form = this.closest('form');

        Swal.fire({
            title: 'Keluar Akun?',
            text: 'Kamu akan keluar dari sesi ini.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,

            width: '380px',
            padding: '1.8em',
            borderRadius: '14px',

            background: '#222831',
            color: '#f1f5f9',

            confirmButtonColor: '#f6973f',
            cancelButtonColor: '#374151',

            backdrop: 'rgba(0,0,0,0.75)',

            customClass: {
                popup: 'smooth-popup',
                title: 'smooth-title',
                confirmButton: 'smooth-confirm',
                cancelButton: 'smooth-cancel'
            }

        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
