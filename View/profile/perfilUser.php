<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../View/login/login.php");
    exit();
}

if ($_SESSION['role'] !== 'user') {
    header("Location: ../../View/profile/perfilDisco.php");
    exit();
}

require_once '../../Model/db.php';
$db = Database::getInstance()->getConexion();

$stmt = $db->prepare("SELECT name, username, photo FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$stmt = null;

// Reserved events + artist info
$stmtRes = $db->prepare(
    "SELECT e.id, e.name, e.date, e.location, e.artist, e.price, e.image,
            r.quantity, r.reserved_at
     FROM reservations r
     JOIN events e ON e.id = r.event_id
     WHERE r.user_id = ?
     ORDER BY e.date ASC"
);
$stmtRes->execute([$_SESSION['user_id']]);
$reservations = $stmtRes->fetchAll();
$stmtRes = null;

$success = $_GET['success'] ?? '';
?>

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
    <label class="sidebar-overlay" for="sidebar-toggle"></label>
    <label class="profile-overlay" for="profile-sidebar-toggle"></label>

    <!-- ── MAIN SIDEBAR ── -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <label class="sidebar-close" for="sidebar-toggle" aria-label="Close">
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/GlobalTicket/View/profile/perfilUser.php">Profile</a>
                <a href="/GlobalTicket/View/map/map.php">Map</a>
                <a href="/GlobalTicket/Controller/logout.php">Log out</a>
            <?php else: ?>
                <a href="/GlobalTicket/View/signIn/signin.php">Sign in</a>
                <a href="/GlobalTicket/View/login/login.php">Log in</a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- ── PROFILE SIDEBAR (purple) ── -->
    <aside class="profile-sidebar">
        <div class="profile-sidebar-top">
            <label class="profile-sidebar-close" for="profile-sidebar-toggle" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </label>
            <button class="profile-sidebar-search" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <circle cx="7.5" cy="7.5" r="5.5" stroke="currentColor" stroke-width="2" />
                    <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <div class="profile-sidebar-search-row">
            <input class="profile-sidebar-input" type="text" placeholder="">
        </div>
        <nav class="profile-sidebar-nav">
            <a href="/GlobalTicket/View/map/map.php">Map</a>
            <a href="/GlobalTicket/Controller/logout.php">Log out</a>
        </nav>
    </aside>

    <!-- ── HEADER ── -->
    <header class="header profile-header">
        <a href="../home/home.php" class="logo">
            <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <label class="menu-btn" for="profile-sidebar-toggle" aria-label="Open menu">
            <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                <line x1="0" y1="1" x2="22" y2="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="8" x2="22" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </label>
    </header>

    <!-- ── MAIN ── -->
    <main class="profile-main">
        <div class="profile-container">

            <?php if ($success === 'reserved'): ?>
                <div class="flash-success">Event reserved successfully!</div>
            <?php endif; ?>

            <!-- Profile banner -->
            <div class="profile-banner">
                <div class="profile-banner-avatar">
                    <?php if (!empty($user['photo'])): ?>
                        <img src="/GlobalTicket/uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Profile photo" class="avatar-img">
                    <?php else: ?>
                        <span class="avatar-plus">+</span>
                    <?php endif; ?>
                </div>
                <div class="profile-banner-info">
                    <h1 class="profile-banner-name"><?= htmlspecialchars($user['name']) ?></h1>
                    <p class="profile-banner-username">@<?= htmlspecialchars($user['username']) ?></p>
                    <a href="../profile/editProfileUser.php" class="profile-edit-btn">Edit profile</a>
                </div>
                <div class="profile-banner-stats">
                    <div class="profile-stat">
                        <span class="profile-stat-num"><?= count($reservations) ?></span>
                        <span class="profile-stat-label">reservations</span>
                    </div>
                </div>
            </div>

            <div class="profile-card" style="display:none">
            </div>

            <!-- My Reservations -->
            <section class="reservations-section">
                <div class="reservations-header">
                    <div class="res-header-left">
                        <h2 class="reservations-title">MY RESERVATIONS</h2>
                        <?php if (!empty($reservations)): ?>
                            <span class="res-count"><?= count($reservations) ?> event<?= count($reservations) !== 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="/GlobalTicket/View/map/map.php" class="view-map-btn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                        View map
                    </a>
                </div>

                <?php if (empty($reservations)): ?>
                    <div class="res-empty">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                        <p>No reservations yet</p>
                        <a href="/GlobalTicket/View/map/map.php" class="res-empty-cta">Browse events</a>
                    </div>
                <?php else: ?>
                    <div class="reservations-grid">
                        <?php foreach ($reservations as $res): ?>
                            <div class="res-card">
                                <div class="res-card-img-wrap">
                                    <?php if (!empty($res['image'])): ?>
                                        <img src="/GlobalTicket/uploads/events/<?= htmlspecialchars($res['image']) ?>" alt="<?= htmlspecialchars($res['name']) ?>" class="res-card-img">
                                    <?php else: ?>
                                        <div class="res-card-img res-card-noimg">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        </div>
                                    <?php endif; ?>
                                    <span class="res-card-ticket-chip">
                                        <?= (int)$res['quantity'] ?> ticket<?= $res['quantity'] > 1 ? 's' : '' ?>
                                    </span>
                                </div>
                                <div class="res-card-body">
                                    <div class="res-card-top">
                                        <p class="res-card-name"><?= htmlspecialchars($res['name']) ?></p>
                                        <?php if (!empty($res['artist'])): ?>
                                            <p class="res-card-artist"><?= htmlspecialchars($res['artist']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="res-card-bottom">
                                        <div class="res-card-details">
                                            <span class="res-card-detail">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                <?= htmlspecialchars(date('d M Y', strtotime($res['date']))) ?>
                                            </span>
                                            <span class="res-card-detail">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <?= htmlspecialchars($res['location']) ?>
                                            </span>
                                        </div>
                                        <p class="res-card-price">
                                            <?= $res['price'] > 0 ? '€' . number_format($res['price'] * $res['quantity'], 2) : 'Free' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

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
