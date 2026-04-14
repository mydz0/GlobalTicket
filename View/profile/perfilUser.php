<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>

    <!-- ══ CSS STATE MACHINE ══ -->
    <input type="checkbox" id="sidebar-toggle">
    <input type="checkbox" id="profile-sidebar-toggle">

    <!-- Overlays -->
    <label class="sidebar-overlay"  for="sidebar-toggle"></label>
    <label class="profile-overlay"  for="profile-sidebar-toggle"></label>

    <!-- ── MAIN SIDEBAR (hamburger menu) ── -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <label class="sidebar-close" for="sidebar-toggle" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </label>
            <button class="sidebar-search-btn" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="2"/>
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <div class="sidebar-search-row">
            <input class="sidebar-input" type="text" placeholder="">
        </div>
        <nav class="sidebar-nav">
            <a href="#">Sign in</a>
            <a href="#">Log in</a>
        </nav>
    </aside>

    <!-- ── PROFILE SIDEBAR (purple, slides from right) ── -->
    <aside class="profile-sidebar">
        <div class="profile-sidebar-top">
            <label class="profile-sidebar-close" for="profile-sidebar-toggle" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </label>
            <button class="profile-sidebar-search" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="2"/>
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <div class="profile-sidebar-search-row">
            <input class="profile-sidebar-input" type="text" placeholder="">
        </div>
        <nav class="profile-sidebar-nav">
            <a href="#">Favoritos</a>
            <a href="#">Eventos</a>
            <a href="../home/home.html">Log out</a>
        </nav>
    </aside>

    <!-- ── HEADER ── -->
    <header class="header profile-header">
        <a href="../home/home.html" class="logo">
            <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <label class="menu-btn" for="profile-sidebar-toggle" aria-label="Open menu">
            <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                <line x1="0" y1="1"  x2="22" y2="1"  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <line x1="0" y1="8"  x2="22" y2="8"  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </label>
    </header>

    <!-- ── MAIN ── -->
    <main class="profile-main">
        <div class="profile-container">

            <h1 class="profile-title">Profile</h1>

            <div class="profile-card">
                <div class="avatar-circle">
                    <span class="avatar-plus">+</span>
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">Lorem ipsum</h2>
                    <p>Lorem ipsum</p>
                    <p>Lorem ipsum jnaf</p>
                    <p>ujhbskjdbfjsbou</p>
                    <a href="/View/login/editProfileUser.html" class="profile-edit-btn">Edit profile</a>
                </div>
            </div>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo-wrap">
                <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
            </div>
            <div class="footer-col">
                <h4>Instagram</h4><p>@globaltickets</p>
                <h4>Email</h4><p>ticket@globaltickets</p>
                <h4>Contact</h4><p>+30 111 111 111</p>
            </div>
            <div class="footer-col">
                <h4>Instagram</h4><p>@globaltickets</p>
                <h4>Email</h4><p>ticket@globaltickets</p>
                <h4>Contact</h4><p>+30 111 111 111</p>
            </div>
            <div class="footer-col">
                <h4>Instagram</h4><p>@globaltickets</p>
                <h4>Email</h4><p>ticket@globaltickets</p>
                <h4>Contact</h4><p>+30 111 111 111</p>
            </div>
        </div>
    </footer>

</body>
</html>