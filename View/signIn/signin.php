<?php
//  signin.php
//  Selector de tipo de registro: usuario o discográfica

session_start();

// Si ya hay sesión activa no tiene sentido registrarse → al perfil
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'disco') {
        header('Location: ../profile/perfilDisco.php');
    } else {
        header('Location: ../profile/perfilUser.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="signin.css">
</head>

<body>

    <input type="checkbox" id="sidebar-toggle">
    <label class="sidebar-overlay" for="sidebar-toggle"></label>

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <label class="sidebar-close" for="sidebar-toggle" aria-label="Close menu">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </label>
            <button class="sidebar-search-btn" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="2" />
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <div class="sidebar-search-row">
            <input class="sidebar-input" type="text" placeholder="">
        </div>
        <nav class="sidebar-nav">
            <a href="signin.php">Sign in</a>
            <a href="../login/login.php">Log in</a>
        </nav>
    </aside>

    <!-- ── HEADER ── -->
    <header class="header signin-header">
        <a href="../home/home.php" class="logo">
            <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <div class="header-right">
            <div class="header-arrows">
                <svg width="22" height="18" viewBox="0 0 22 18" fill="none">
                    <polyline points="7,2 2,9 7,16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                    <polyline points="15,2 20,9 15,16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                </svg>
            </div>
            <label class="menu-btn" for="sidebar-toggle" aria-label="Open menu">
                <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                    <line x1="0" y1="1"  x2="22" y2="1"  stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="0" y1="8"  x2="22" y2="8"  stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </label>
        </div>
    </header>

    <!-- ── MAIN ── -->
    <main class="signin-main">
        <div class="signin-container">

            <h1 class="signin-title">Sign in</h1>

            <div class="signin-options">

                <!-- Al hacer clic → formulario de registro de usuario -->
                <a href="user/registerUser.php" class="signin-option">
                    <div class="signin-icon">
                        <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="28" r="18" fill="#aaa" />
                            <path d="M8 72c0-17.673 14.327-32 32-32s32 14.327 32 32" fill="#aaa" />
                        </svg>
                    </div>
                    <span class="signin-label">user</span>
                </a>

                <!-- Al hacer clic → formulario de registro de discográfica -->
                <a href="discography/discoSignIn.php" class="signin-option">
                    <div class="signin-icon">
                        <svg viewBox="0 0 90 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="36" cy="26" r="17" fill="#aaa" />
                            <path d="M4 70c0-17.673 14.327-32 32-32s32 14.327 32 32" fill="#aaa" />
                            <rect x="68" y="34" width="6" height="22" rx="1" fill="#aaa" />
                            <ellipse cx="65" cy="57" rx="6" ry="4" fill="#aaa" />
                            <rect x="74" y="28" width="12" height="6" rx="1" fill="#aaa" />
                        </svg>
                    </div>
                    <span class="signin-label">discography</span>
                </a>

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
