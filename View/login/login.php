<?php
//lamada al controlador
require_once '../../Controller/useController.php';
// require_once '../GlobalTicket/Model/db.php'; // ya incluido via useController.php

// $database = new Database();
// $conn = $database->getConexion();

//control de errores login, username y password
$error_msg = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'credenciales') $error_msg = "Username or password incorrect";
    if ($_GET['error'] === 'error_login') $error_msg = "Logon error, try again";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new useController();
    $controlador->login($_POST);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="login.css">
</head>

<!--Arreglar problema css-->

<body>

    <!-- ══ CSS STATE MACHINE ══ -->
    <input type="checkbox" id="sidebar-toggle">
    <input type="radio" id="pick-user" name="login-type">
    <input type="radio" id="pick-disco" name="login-type">
    <input type="checkbox" id="err-user-username">
    <input type="checkbox" id="err-user-password">
    <input type="checkbox" id="err-disco-username">
    <input type="checkbox" id="err-disco-password">
    <input type="checkbox" id="modal-user">
    <input type="checkbox" id="modal-disco">
    <input type="checkbox" id="err-modal-user">
    <input type="checkbox" id="err-modal-disco">

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
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <div class="sidebar-search-row">
            <input class="sidebar-input" type="text" placeholder="">
        </div>
        <nav class="sidebar-nav">
            <a href="../signIn/signin.php">Sign in</a>
            <a href="#">Log in</a>
        </nav>
    </aside>

    <!-- ── HEADER ── -->
    <header class="header login-header">
        <a href="../home/home.php" class="logo">
            <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <div class="header-right">
            <div class="header-arrows">
                <svg width="22" height="18" viewBox="0 0 22 18" fill="none">
                    <polyline points="7,2 2,9 7,16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" fill="none" />
                    <polyline points="15,2 20,9 15,16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" fill="none" />
                </svg>
            </div>
            <label class="menu-btn" for="sidebar-toggle" aria-label="Open menu">
                <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                    <line x1="0" y1="1" x2="22" y2="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="0" y1="8" x2="22" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </label>
        </div>
    </header>

    <!-- ── MAIN ── -->
    <main class="login-main">
        <div class="login-container">

            <!-- ── SCREEN 1: Selector ── -->
            <div class="screen screen-selector">
                <h1 class="login-title">Log in</h1>
                <div class="login-options">

                    <label class="login-option" for="pick-user">
                        <div class="login-icon">
                            <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="28" r="18" fill="#aaa" />
                                <path d="M8 72c0-17.673 14.327-32 32-32s32 14.327 32 32" fill="#aaa" />
                            </svg>
                        </div>
                        <span class="login-label">user</span>
                    </label>

                    <label class="login-option" for="pick-disco">
                        <div class="login-icon">
                            <svg viewBox="0 0 90 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="26" r="17" fill="#aaa" />
                                <path d="M4 70c0-17.673 14.327-32 32-32s32 14.327 32 32" fill="#aaa" />
                                <rect x="68" y="34" width="6" height="22" rx="1" fill="#aaa" />
                                <ellipse cx="65" cy="57" rx="6" ry="4" fill="#aaa" />
                                <rect x="74" y="28" width="12" height="6" rx="1" fill="#aaa" />
                            </svg>
                        </div>
                        <span class="login-label">discography</span>
                    </label>

                </div>
            </div>

            <!-- ── SCREEN 2: Login USER ── -->
            <div class="screen screen-user">
                <h1 class="login-title">Log in user</h1>
                <div class="form-block">
                    <form method="post" action="">
                        <! -- arriba esta el if($_SERVER ) q solo se ejecutara si volvemos a esta pagina -->

                            <span class="field-lbl">Username</span>
                            <input class="field-input" type="text" name="username" autocomplete="off">
                            <label class="field-error err-user-username" for="err-user-username">Username incorrect</label>

                            <span class="field-lbl">Password</span>
                            <input class="field-input" type="password" name="password">
                            <label class="field-error err-user-password" for="err-user-password">Password incorrect</label>

                            <label class="forgot-link" for="modal-user">Forgot password?</label>
                            <input type="hidden" name="tipo" value="user">
                            <?php if ($error_msg): ?>
                                <p style="color:red;"><?= $error_msg ?></p>
                            <?php endif; ?>

                            <button class="btn-login" type="submit">Log in</button>
                    </form>
                </div>

                <!-- Modal forgot password -->
                <label class="modal-overlay" for="modal-user"></label>
                <div class="modal">
                    <h2 class="modal-title">Forgot password?</h2>

                    <span class="field-lbl">Enter mail or phone number</span>
                    <div class="field-line-static"></div>

                    <span class="field-lbl">Confirm mail or phone number</span>
                    <div class="field-line-static"></div>

                    <label class="field-error err-modal-user" for="err-modal-user">Mail or phone number doesn't match</label>

                    <label class="btn-modal" for="err-modal-user">Send mail</label>
                    <button class="btn-modal" type="button">Send SMS</button>
                </div>
            </div>

            <!-- ── SCREEN 3: Login DISCOGRAPHY ── -->
            <div class="screen screen-disco">
                <h1 class="login-title">Log in discography</h1>
                <div class="form-block">
                    <form method="post" action="">

                        <span class="field-lbl">CIF</span>
                        <input class="field-input" type="text" name="cif" autocomplete="off">
                        <label class="field-error err-disco-username" for="err-disco-username">Username incorrect</label>

                        <span class="field-lbl">Password</span>
                        <input class="field-input" type="password" name="password">
                        <label class="field-error err-disco-password" for="err-disco-password">Password incorrect</label>

                        <label class="forgot-link" for="modal-disco">Forgot password?</label>
                        <input type="hidden" name="tipo" value="disco">
                        <?php if ($error_msg): ?>
                            <p style="color:red;"><?= $error_msg ?></p>
                        <?php endif; ?>

                        <button class="btn-login" type="submit">Log in</button>
                    </form>

                </div>

                <!-- Modal forgot password -->
                <label class="modal-overlay" for="modal-disco"></label>
                <div class="modal">
                    <h2 class="modal-title">Forgot password?</h2>

                    <span class="field-lbl">Enter mail or phone number</span>
                    <div class="field-line-static"></div>

                    <span class="field-lbl">Confirm mail or phone number</span>
                    <div class="field-line-static"></div>

                    <label class="field-error err-modal-disco" for="err-modal-disco">Mail or phone number doesn't match</label>

                    <label class="btn-modal" for="err-modal-disco">Send mail</label>
                    <button class="btn-modal" type="button">Send SMS</button>
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
                <h4>Instagram</h4>
                <p>@globaltickets</p>
                <h4>Email</h4>
                <p>ticket@globaltickets</p>
                <h4>Contact</h4>
                <p>+30 111 111 111</p>
            </div>
            <div class="footer-col">
                <h4>Instagram</h4>
                <p>@globaltickets</p>
                <h4>Email</h4>
                <p>ticket@globaltickets</p>
                <h4>Contact</h4>
                <p>+30 111 111 111</p>
            </div>
            <div class="footer-col">
                <h4>Instagram</h4>
                <p>@globaltickets</p>
                <h4>Email</h4>
                <p>ticket@globaltickets</p>
                <h4>Contact</h4>
                <p>+30 111 111 111</p>
            </div>
        </div>
    </footer>

</body>

</html>