<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /GlobalTicket/View/login/login.php");
    exit();
}

if ($_SESSION['role'] !== 'disco') {
    header("Location: /GlobalTicket/View/profile/perfilUser.php");
    exit();
}

require_once '../../Controller/useController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new useController();
    $controlador->updateDisco($_POST, $_FILES);
}

$error_msg = '';
if (isset($_GET['error'])) {
    $errors = [
        'email'        => 'Please enter a valid email address',
        'password'     => 'Passwords do not match',
        'photo'        => 'Invalid file type. Allowed: jpg, jpeg, png, webp, gif',
        'email_exists' => 'That email is already in use',
        'db_error'     => 'Could not save changes, please try again',
    ];
    $error_msg = $errors[$_GET['error']] ?? 'Unknown error';
}

require_once '../../Model/db.php';
$db   = Database::getInstance()->getConexion();
$stmt = $db->prepare("SELECT name, cif, mail, cellphone, adress, photo FROM discographies WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$disco = $stmt->get_result()->fetch_assoc();
$stmt->close();
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
            <a href="/GlobalTicket/View/profile/perfilDisco.php">Profile</a>
            <a href="#">Favoritos</a>
            <a href="#">Eventos</a>
            <a href="/GlobalTicket/Controller/logout.php">Log out</a>
        </nav>
    </aside>

    <header class="header">
        <a href="../home/home.php" class="logo">
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

            <?php if ($error_msg): ?>
                <p style="color:red; margin-bottom:1rem;"><?= htmlspecialchars($error_msg) ?></p>
            <?php endif; ?>

            <form class="edit-form" action="/GlobalTicket/View/profile/editProfileDisco.php" method="post" enctype="multipart/form-data">
                <div class="edit-layout">
                    <div class="edit-fields">

                        <div class="field-group">
                            <label class="field-label" for="name">Name</label>
                            <input class="field-input" type="text" id="name" name="name" required placeholder=" "
                                value="<?= htmlspecialchars($disco['name']) ?>">
                            <span class="field-error">Please provide a name</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="cif">CIF</label>
                            <input class="field-input" type="text" id="cif" value="<?= htmlspecialchars($disco['cif']) ?>"
                                disabled placeholder=" ">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="mail">Mail</label>
                            <input class="field-input" type="email" id="mail" name="mail" required placeholder=" "
                                value="<?= htmlspecialchars($disco['mail']) ?>">
                            <span class="field-error">Please enter a valid email address</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="cellphone">Cellphone</label>
                            <input class="field-input" type="tel" id="cellphone" name="cellphone" required
                                pattern="[0-9+\s\-]{7,15}" placeholder=" "
                                value="<?= htmlspecialchars($disco['cellphone'] ?? '') ?>">
                            <span class="field-error">Please enter a phone number</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="adress">Address</label>
                            <input class="field-input" type="text" id="adress" name="adress" required placeholder=" "
                                value="<?= htmlspecialchars($disco['adress'] ?? '') ?>">
                            <span class="field-error">Please enter an address</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="password">New password</label>
                            <input class="field-input" type="password" id="password" name="password"
                                minlength="6" placeholder=" ">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="confirm">Confirm new password</label>
                            <input class="field-input" type="password" id="confirm" name="confirm-password"
                                minlength="6" placeholder=" ">
                            <span class="field-error">Password doesn't match</span>
                        </div>

                    </div>
                    <div class="edit-photo">
                        <label class="photo-upload-circle" for="photo-input">
                            <?php if (!empty($disco['photo'])): ?>
                                <img src="/GlobalTicket/View/uploads/<?= htmlspecialchars($disco['photo']) ?>"
                                    alt="Profile photo" class="avatar-img">
                            <?php else: ?>
                                <span class="photo-plus">+</span>
                            <?php endif; ?>
                            <input type="file" id="photo-input" name="photo" accept="image/*" hidden>
                        </label>
                        <p class="photo-label">Add photo</p>
                    </div>
                </div>
                <div class="edit-btns">
                    <button class="edit-btn" type="submit">Confirm</button>
                    <a class="edit-btn edit-btn--danger" href="../home/home.php">Delete account</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo-wrap"><img src="../home/logo.svg" alt="Global Tickets" class="logo-img"></div>
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
