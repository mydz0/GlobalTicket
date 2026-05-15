<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /GlobalTicket/View/login/login.php");
    exit();
}

require_once '../../Model/db.php';
$db = Database::getInstance()->getConexion();

$stmt = $db->prepare("SELECT name, cif, photo FROM discographies WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$stmt = null;

if (!$user) {
    header("Location: /GlobalTicket/View/login/login.php");
    exit();
}

// My events
$stmtEv = $db->prepare(
    "SELECT id, name, date, location, artist, price, image
     FROM events
     WHERE discography_id = ?
     ORDER BY date ASC"
);
$stmtEv->execute([$_SESSION['user_id']]);
$myEvents = $stmtEv->fetchAll();
$stmtEv = null;

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
    <link rel="stylesheet" href="perfilDisco.css">
</head>

<body>

    <!-- ══ CSS STATE MACHINE ══ -->
    <input type="checkbox" id="sidebar-toggle">
    <input type="checkbox" id="profile-sidebar-toggle">

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
                <a href="/GlobalTicket/View/profile/perfilDisco.php">Profile</a>
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
            <a href="/GlobalTicket/View/event/addEvent.php">Add event</a>
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

            <?php if ($success === 'event_created'): ?>
                <div class="flash-success">Event created successfully!</div>
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
                    <p class="profile-banner-username"><?= htmlspecialchars($user['cif']) ?></p>
                    <a href="/GlobalTicket/View/profile/editProfileDisco.php" class="profile-edit-btn">Edit profile</a>
                </div>
                <div class="profile-banner-stats">
                    <div class="profile-stat">
                        <span class="profile-stat-num"><?= count($myEvents) ?></span>
                        <span class="profile-stat-label">events</span>
                    </div>
                </div>
            </div>

            <!-- My Events -->
            <section class="events-section">
                <div class="artists-header">
                    <h2 class="artists-title">MY EVENTS</h2>
                    <a href="/GlobalTicket/View/event/addEvent.php" class="add-artist-btn">+ Add event</a>
                </div>

                <?php if (empty($myEvents)): ?>
                    <p class="no-items-msg">No events yet. <a href="/GlobalTicket/View/event/addEvent.php">Add your first event.</a></p>
                <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($myEvents as $ev): ?>
                            <div class="event-card-disco">
                                <?php if (!empty($ev['image'])): ?>
                                    <img src="/GlobalTicket/uploads/events/<?= htmlspecialchars($ev['image']) ?>" alt="<?= htmlspecialchars($ev['name']) ?>" class="event-card-img">
                                <?php else: ?>
                                    <div class="event-card-img event-card-noimg">No image</div>
                                <?php endif; ?>
                                <div class="event-card-body">
                                    <p class="event-card-name"><?= htmlspecialchars($ev['name']) ?></p>
                                    <?php if (!empty($ev['artist'])): ?>
                                        <p class="event-card-artist"><?= htmlspecialchars($ev['artist']) ?></p>
                                    <?php endif; ?>
                                    <p class="event-card-meta">
                                        <?= htmlspecialchars(date('d M Y, H:i', strtotime($ev['date']))) ?>
                                    </p>
                                    <p class="event-card-meta"><?= htmlspecialchars($ev['location']) ?></p>
                                    <p class="event-card-price">
                                        <?= $ev['price'] > 0 ? '€' . number_format($ev['price'], 2) : 'Free' ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- My Artists -->
            <section class="artists-section" style="margin-top:48px">
                <div class="artists-header">
                    <h2 class="artists-title">MY ARTISTS</h2>
                    <a href="#" class="add-artist-btn">Add artist</a>
                </div>
                <div class="artists-grid">
                    <a href="#" class="artist-card">
                        <img src="addison.jpg" alt="Addison Rae">
                        <p class="artist-name">Addison Rae</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="tyler.jpg" alt="Tyler, The Creator">
                        <p class="artist-name">Tyler, The Creator</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="solange.jpg" alt="Solange">
                        <p class="artist-name">Solange</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="jennie.jpg" alt="JENNIE">
                        <p class="artist-name">JENNIE</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="ian.jpg" alt="ian">
                        <p class="artist-name">ian</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="centralcee.jpg" alt="Central Cee">
                        <p class="artist-name">Central Cee</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="tameimpala.jpg" alt="Tame Impala">
                        <p class="artist-name">Tame Impala</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="ive.jpg" alt="IVE">
                        <p class="artist-name">IVE</p>
                    </a>
                    <a href="#" class="artist-card">
                        <img src="rosalia.jpg" alt="Rosalía">
                        <p class="artist-name">Rosalía</p>
                    </a>
                </div>
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
