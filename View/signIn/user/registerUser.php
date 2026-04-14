<?php

//llamar el controlador para usarlo
require_once '../../Controller/useController.php';

//mensaje que sale para cuando las contraseñas no coinciden:
$error_msg = "";
if (isset($_GET['error']) && $_GET['error'] === "password") {
    $error_msg = "Password not match";
}

//cuando el usuario le da al boton (llamamos al metodo que pusimos):
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //con esto creamos el objeto 
    $controlador = new useController();
    //usamos el metodo register que pusimos en la clase, 
    $controlador->register($_POST, $_FILES);
    //ESTE RECIBE LOS DATOS DEL FORMULARIO 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create user account — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../home/home.css">
    <link rel="stylesheet" href="registerUser.css">
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
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
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

    <!-- ── HEADER ── -->
    <header class="header">
        <a href="../../home/home.html" class="logo">
            <img src="../../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <label class="menu-btn" for="sidebar-toggle" aria-label="Open menu">
            <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                <line x1="0" y1="1" x2="22" y2="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="8" x2="22" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </label>
    </header>

    <!-- ── MAIN ── -->
    <main class="register-main">
        <div class="register-container">

            <h1 class="register-title">Create user account</h1>

            <form class="register-form" action="" method="post" enctype="multipart/form-data">
                <!--enctype para subir foto del usuario-->

                <div class="register-layout">

                    <!-- LEFT: campos -->
                    <div class="register-fields">

                        <div class="field-group">
                            <label class="field-label" for="name">Name</label>
                            <input class="field-input" type="text" id="name" name="name" required placeholder=" ">
                            <span class="field-error">Please provide a name</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="surname">Surname</label>
                            <input class="field-input" type="text" id="surname" name="surname" required placeholder=" ">
                            <span class="field-error">Please provide a surname</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="mail">Mail</label>
                            <input class="field-input" type="email" id="mail" name="mail" required placeholder=" ">
                            <span class="field-error">Please enter a valid email adress</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="cellphone">Cellphone</label>
                            <input class="field-input" type="tel" id="cellphone" name="cellphone" required
                                pattern="[0-9+\s\-]{7,15}" placeholder=" ">
                            <span class="field-error">Please enter a phone number</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="username">Username</label>
                            <input class="field-input" type="text" id="username" name="username" required minlength="3"
                                pattern="[a-zA-Z0-9_]{3,}" placeholder=" ">
                            <span class="field-error">Username not valid</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="password">Password</label>
                            <input class="field-input" type="password" id="password" name="password" required
                                minlength="6" placeholder=" ">
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="confirm-password">Confirm password</label>
                            <?php if ($error_msg) : ?>
                                <p style = "color:red;"><?= $error_msg ?></p>
                                <?php endif; ?>
                            <input class="field-input" type="password" id="confirm-password" name="confirm-password"
                                required minlength="6" placeholder=" ">
                            <span class="field-error">Password doesn't match</span>
                        </div>

                    </div>

                    <!-- RIGHT: foto -->
                    <div class="register-photo">
                        <label class="photo-upload-circle" for="photo-input">
                            <span class="photo-plus">+</span>
                            <input type="file" id="photo-input" name="photo" accept="image/*" hidden>
                        </label>
                        <p class="photo-label">Add photo</p>
                    </div>

                </div>

                <!-- Botones -->
                <div class="register-btns">
                    <button class="reg-btn reg-btn--primary" type="submit">Create
                        account</button>
                    <a class="reg-btn reg-btn--secondary" href="../../home/home.html">Cancel</a>
                </div>

            </form>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo-wrap">
                <img src="../../home/logo.svg" alt="Global Tickets" class="logo-img">
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