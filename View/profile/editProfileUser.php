<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once '../../Controller/useController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new useController();
    $controlador->updateUser($_POST, $_FILES);
}

$error_msg = '';
if (isset($_GET['error'])) {
    $errors = [
        'email'        => 'Please enter a valid email address',
        'username'     => 'Username must be at least 3 characters (letters, numbers and _)',
        'password'     => 'Passwords do not match',
        'photo'        => 'Invalid file type. Allowed: jpg, jpeg, png, webp, gif',
        'email_exists' => 'That email or username is already in use',
        'db_error'     => 'Could not save changes, please try again',
    ];
    $error_msg = $errors[$_GET['error']] ?? 'Unknown error';
}

require_once '../../Model/db.php';
$db   = Database::getInstance()->getConexion();
$stmt = $db->prepare("SELECT name, surname, mail, cellphone, username, photo FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
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
            <a href="/GlobalTicket/View/profile/perfilUser.php">Profile</a>
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

            <form class="edit-form" action="/GlobalTicket/View/profile/editProfileUser.php" method="post" enctype="multipart/form-data">
                <div class="edit-layout">
                    <div class="edit-fields">

                        <div class="field-group">
                            <label class="field-label" for="name">Name</label>
                            <input class="field-input" type="text" id="name" name="name" required placeholder=" "
                                value="<?= htmlspecialchars($user['name']) ?>">
                            <span class="field-error">Please provide a name</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="surname">Surname</label>
                            <input class="field-input" type="text" id="surname" name="surname" required placeholder=" "
                                value="<?= htmlspecialchars($user['surname']) ?>">
                            <span class="field-error">Please provide a surname</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="mail">Mail</label>
                            <input class="field-input" type="email" id="mail" name="mail" required placeholder=" "
                                value="<?= htmlspecialchars($user['mail']) ?>">
                            <span class="field-error">Please enter a valid email address</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="cellphone">Cellphone</label>
                            <input class="field-input" type="tel" id="cellphone" name="cellphone" required
                                pattern="[0-9+\s\-]{7,15}" placeholder=" "
                                value="<?= htmlspecialchars($user['cellphone'] ?? '') ?>">
                            <span class="field-error">Please enter a phone number</span>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="username">Username</label>
                            <input class="field-input" type="text" id="username" name="username" required
                                minlength="3" pattern="[a-zA-Z0-9_]{3,}" placeholder=" "
                                value="<?= htmlspecialchars($user['username']) ?>">
                            <span class="field-error">Username not valid</span>
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
                            <?php if (!empty($user['photo'])): ?>
                                <img src="/GlobalTicket/View/uploads/<?= htmlspecialchars($user['photo']) ?>"
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
                    <button type="submit" form="delete-account-form" class="edit-btn edit-btn--danger"
                            onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete account</button>
                </div>
            </form>

            <!-- Delete form (fuera del form principal para no anidarlo) -->
            <form method="POST" action="/GlobalTicket/Controller/deleteAccount.php" id="delete-account-form"></form>

            <!-- Change password -->
            <section class="pw-section">
                <h2 class="pw-title">Change password</h2>

                <form class="edit-form" method="POST" action="/GlobalTicket/Controller/changePassword.php">
                    <div class="pw-fields">
                        <div class="field-group">
                            <label class="field-label" for="current-password">Current password</label>
                            <input class="field-input" type="password" id="current-password" name="current-password" required minlength="6" placeholder=" ">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="new-password">New password</label>
                            <input class="field-input" type="password" id="new-password" name="new-password" required minlength="6" placeholder=" ">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="confirm-password">Confirm new password</label>
                            <input class="field-input" type="password" id="confirm-password" name="confirm-password" required minlength="6" placeholder=" ">
                        </div>
                    </div>
                    <div class="edit-btns">
                        <button class="edit-btn" type="submit">Change password</button>
                    </div>
                </form>

                <?php if (isset($_GET['success'])): ?>
                    <p class="pw-flash pw-flash--ok">Password updated successfully.</p>
                <?php elseif (isset($_GET['error']) && in_array($_GET['error'], ['wrong_password','password_mismatch','password_short','missing_fields'])): ?>
                    <?php
                    $msgs = [
                        'wrong_password'    => 'Current password is incorrect.',
                        'password_mismatch' => 'New passwords do not match.',
                        'password_short'    => 'New password must be at least 6 characters.',
                        'missing_fields'    => 'Please fill in all fields.',
                    ];
                    ?>
                    <p class="pw-flash pw-flash--err"><?= htmlspecialchars($msgs[$_GET['error']]) ?></p>
                <?php endif; ?>
            </section>
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
