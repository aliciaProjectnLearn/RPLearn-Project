<nav class="navbar">
    <div class="navbar-title">
        <h3>Welcome, {{ auth()->user()->username }}</h3>
    </div>

    <div class="navbar-user" style="position: relative; margin-right: 3rem;">

        <div onclick="toggleProfileMenu()" style="cursor: pointer; display: flex; align-items: center; gap: 15px; user-select: none;">
            <span style="color: white; font-weight: 600; font-size: 1rem;">
                {{ Auth::user()->username }} <i class="fa-solid fa-caret-down" style="margin-left: 5px; font-size: 0.8rem;"></i>
            </span>

            <div style="width: 45px; height: 45px; background-color: #F6973F; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(246, 151, 63, 0.3);">
                {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
            </div>
        </div>

        <div id="profileDropdown" style="display: none; position: absolute; top: 60px; right: 0; background-color: white; min-width: 220px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.15); overflow: hidden; z-index: 9999; border: 1px solid #eee;">

            <div style="padding: 16px 20px; background-color: #f8f9fa; border-bottom: 1px solid #eee;">
                <p style="margin: 0; font-size: 0.8rem; color: #888;">Masuk sebagai</p>
                <p style="margin: 0; font-weight: bold; color: #222831; font-size: 1rem;">{{ Auth::user()->username }}</p>
            </div>

            <a href="{{ route('profile.edit') }}" style="display: block; padding: 14px 20px; color: #393E46; text-decoration: none; font-size: 0.95rem; font-weight: 500; transition: 0.2s;" onmouseover="this.style.background='#f1f1f1'" onmouseout="this.style.background='white'">
                <i class="fa-solid fa-user-gear" style="width: 25px; color: #F6973F;"></i> Pengaturan Profil
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin: 0; border-top: 1px solid #eee;">
                @csrf
                <button type="submit" style="display: block; width: 100%; text-align: left; background: white; border: none; padding: 14px 20px; color: #d63031; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='white'">
                    <i class="fa-solid fa-right-from-bracket" style="width: 25px;"></i> Keluar
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
</script>
