<?php
session_start();

//redirigir directamente al log in si este no está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit profile — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="editperfil.css">
</head>

<body>

    <input type="checkbox" id="sidebar-toggle">
    <label class="sidebar-overlay" for="sidebar-toggle"></label>

    <aside class="sidebar">
        <div class="sidebar-top">
            <label class="sidebar-close" for="sidebar-toggle">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </label>
            <button class="sidebar-search-btn">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="2" />
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <div class="sidebar-search-row"><input class="sidebar-input" type="text" placeholder=""></div>
        <nav class="sidebar-nav">
            <a href="#">Sign in</a>
            <a href="#">Log in</a>
        </nav>
    </aside>

    <header class="header">
        <a href="../home/home.html" class="logo">
            <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <label class="menu-btn" for="sidebar-toggle">
            <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                <line x1="0" y1="1" x2="22" y2="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="8" x2="22" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </label>
    </header>

    <main class="edit-main">
        <div class="edit-container">
            <h1 class="edit-title">Edit profile</h1>
            <form class="edit-form" action="profile-user.html" method="get">
                <div class="edit-layout">
                    <div class="edit-fields">

                        <div class="field-group">
                            <label class="field-label" for="name">Name</label>
                            <input class="field-input" type="text" id="name" required placeholder=" ">
                            <span class="field-error">Please provide a name</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="surname">Surname</label>
                            <input class="field-input" type="text" id="surname" required placeholder=" ">
                            <span class="field-error">Please provide a surname</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="mail">Mail</label>
                            <input class="field-input" type="email" id="mail" required placeholder=" ">
                            <span class="field-error">Please enter a valid email adress</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="cellphone">Cellphone</label>
                            <input class="field-input" type="tel" id="cellphone" required pattern="[0-9+\s\-]{7,15}" placeholder=" ">
                            <span class="field-error">Please enter a phone number</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="username">Username</label>
                            <input class="field-input" type="text" id="username" required minlength="3" pattern="[a-zA-Z0-9_]{3,}" placeholder=" ">
                            <span class="field-error">Username not valid</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="password">Password</label>
                            <input class="field-input" type="password" id="password" required minlength="6" placeholder=" ">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="confirm">Confirm password</label>
                            <input class="field-input" type="password" id="confirm" required minlength="6" placeholder=" ">
                            <span class="field-error">Password doesn't match</span>
                        </div>

                    </div>
                    <div class="edit-photo">
                        <label class="photo-upload-circle" for="photo-input">
                            <span class="photo-plus">+</span>
                            <input type="file" id="photo-input" accept="image/*" hidden>
                        </label>
                        <p class="photo-label">Add photo</p>
                    </div>
                </div>
                <div class="edit-btns">
                    <button class="edit-btn" type="submit">Confirm</button>
                    <a class="edit-btn edit-btn--danger" href="../home/home.html">Delete account</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo-wrap"><img src="/View/home/logo.svg" alt="Global Tickets" class="logo-img"></div>
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