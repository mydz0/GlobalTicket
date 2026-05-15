<?php
session_start();
require_once '../../Model/db.php';
$db = Database::getInstance()->getConexion();

$stmtCount = $db->query("SELECT COUNT(*) FROM events");
$eventCount = (int)$stmtCount->fetchColumn();

$stmtMapEvents = $db->query(
    "SELECT id, name, date, location, artist, price, image, latitude, longitude
     FROM events WHERE latitude IS NOT NULL AND longitude IS NOT NULL ORDER BY date ASC"
);
$mapEvents = $stmtMapEvents->fetchAll(PDO::FETCH_ASSOC);
// Upcoming events for horizontal scroll (all, ordered by date)
$stmtUpcoming = $db->query(
    "SELECT id, name, date, location, artist, price FROM events ORDER BY date ASC LIMIT 8"
);
$upcomingEvents = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

$mapEventsJson = json_encode(array_map(fn($e) => [
    'name'     => $e['name'],
    'artist'   => $e['artist'] ?? '',
    'date'     => date('d M Y', strtotime($e['date'])),
    'location' => $e['location'],
    'price'    => $e['price'] > 0 ? '€'.number_format($e['price'],2) : 'Free',
    'image'    => $e['image'] ? '/GlobalTicket/uploads/events/'.$e['image'] : '',
    'lat'      => (float)$e['latitude'],
    'lng'      => (float)$e['longitude'],
], $mapEvents));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="home.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>

<body>

    <!-- ── HIDDEN CHECKBOX – CSS-only sidebar toggle ── -->
    <input type="checkbox" id="sidebar-toggle">

    <!-- Clicking overlay closes sidebar -->
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php $profileUrl = $_SESSION['role'] === 'disco' ? '/GlobalTicket/View/profile/perfilDisco.php' : '/GlobalTicket/View/profile/perfilUser.php'; ?>
                <a href="<?= $profileUrl ?>">Profile</a>
                <a href="#">Favoritos</a>
                <a href="#">Eventos</a>
                <a href="/GlobalTicket/Controller/logout.php">Log out</a>
            <?php else: ?>
                <a href="../signIn/signin.php">Sign in</a>
                <a href="../login/login.php">Log in</a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- ── HEADER ── -->
    <header class="header">
        <a href="#" class="logo">
            <img src="logo.svg" alt="Global Tickets" class="logo-img">
        </a>

        <!-- Menu button – label toggles checkbox -->
        <label class="menu-btn" for="sidebar-toggle" aria-label="Open menu">
            <svg class="icon-hamburger" width="22" height="16" viewBox="0 0 22 16" fill="none">
                <line x1="0" y1="1" x2="22" y2="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="8" x2="22" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </label>
    </header>

    <!-- ── HERO ── -->
    <section class="hero">
        <img src="theMarias.jpg" alt="The Marias" class="hero-img">
        <div class="hero-overlay">
            <p class="hero-eyebrow">Global Tickets</p>
            <h1 class="hero-title">The best<br>live events</h1>
            <p class="hero-cities">Barcelona &nbsp;·&nbsp; Madrid &nbsp;·&nbsp; London &nbsp;·&nbsp; Paris &nbsp;·&nbsp; Berlin</p>
        </div>
    </section>

    <!-- ── UPCOMING EVENTS (horizontal scroll) ── -->
    <?php if (!empty($upcomingEvents)): ?>
    <section class="upcoming-section reveal">
        <div class="upcoming-head">
            <h2 class="upcoming-title">UPCOMING</h2>
            <a href="/GlobalTicket/View/map/map.php" class="upcoming-all">See all &rarr;</a>
        </div>
        <div class="upcoming-track">
            <?php foreach ($upcomingEvents as $ev):
                $month = strtoupper(date('M', strtotime($ev['date'])));
                $day   = date('d',  strtotime($ev['date']));
                $year  = date('Y',  strtotime($ev['date']));
            ?>
            <a href="../event/event.php" class="upcoming-card">
                <div class="upcoming-date">
                    <span class="upcoming-month"><?= $month ?></span>
                    <span class="upcoming-day"><?= $day ?></span>
                    <span class="upcoming-year"><?= $year ?></span>
                </div>
                <div class="upcoming-info">
                    <p class="upcoming-artist"><?= htmlspecialchars($ev['artist'] ?: $ev['name']) ?></p>
                    <p class="upcoming-event"><?= htmlspecialchars($ev['name']) ?></p>
                    <p class="upcoming-loc"><?= htmlspecialchars($ev['location']) ?></p>
                </div>
                <p class="upcoming-price">
                    <?= $ev['price'] > 0 ? '€'.number_format($ev['price'],2) : 'Free' ?>
                </p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── MAIN GRID ── -->
    <main class="container reveal">
        <div class="grid">

            <a class="card" href="../event/event.php">
                <div class="card-img-wrap">
                    <img src="wte.jpg" alt="Wave to Earth">
                    <div class="card-info">
                        <p class="card-desc">Lorem ipsum dolor sit amet</p>
                        <h3>Wave to Earth</h3>
                    </div>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <div class="card-img-wrap">
                    <img src="rusowsky.webp" alt="Schoolgirl byebye">
                    <div class="card-info">
                        <p class="card-desc">Lorem ipsum dolor sit amet</p>
                        <h3>Schoolgirl byebye</h3>
                    </div>
                </div>
            </a>

            <!-- Tall ad – spans 3 rows -->
            <div class="card ad-tall">
                <img src="ad.jpg" alt="Ad">
            </div>

            <a class="card" href="../event/event.php">
                <div class="card-img-wrap">
                    <img src="frankOcean.jpg" alt="Frank Ocean">
                    <div class="card-info">
                        <p class="card-desc">Lorem ipsum dolor sit amet</p>
                        <h3>Frank Ocean</h3>
                    </div>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <div class="card-img-wrap">
                    <img src="kaliUchis.webp" alt="Kali Uchis">
                    <div class="card-info">
                        <p class="card-desc">Lorem ipsum dolor sit amet</p>
                        <h3>Kali Uchis</h3>
                    </div>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <div class="card-img-wrap">
                    <img src="pinkpantheress2.webp" alt="PinkPanthress">
                    <div class="card-info">
                        <p class="card-desc">Lorem ipsum dolor sit amet</p>
                        <h3>PinkPanthress</h3>
                    </div>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <div class="card-img-wrap">
                    <img src="charliXCX.webp" alt="Charli XCX">
                    <div class="card-info">
                        <p class="card-desc">Lorem ipsum dolor sit amet</p>
                        <h3>Charli XCX</h3>
                    </div>
                </div>
            </a>

            <!-- Bottom ad -->
            <div class="card ad-bottom">
                <img src="ad.jpg" alt="Ad">
            </div>

            <!-- Countdown -->
            <div class="card countdown-card">
                <h2 class="countdown-title">COUNTDOWN</h2>
                <div class="timer" id="countdown">04:10:20</div>
                <button class="reserve-btn">RESERVA AHORA</button>
            </div>

        </div>
    </main>

    <!-- ── MAP SECTION ── -->
    <section class="home-map-section reveal">
        <div class="home-map-inner">

            <!-- Header text -->
            <div class="home-map-header">
                <p class="home-map-eyebrow">— Live events —</p>
                <h2 class="home-map-title">Find events <span>on the map</span></h2>
                <p class="home-map-sub">Discover concerts, festivals and live shows happening around the world.</p>
            </div>

            <!-- Map container -->
            <div class="home-map-wrap">
                <div id="home-mini-map" style="width:100%;height:360px;"></div>
            </div>

            <!-- Footer row -->
            <div class="home-map-footer">
                <a href="/GlobalTicket/View/map/map.php" class="home-map-cta">
                    Explore full map
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer class="footer">
        <div class="footer-inner">

            <!-- Brand column -->
            <div class="footer-brand">
                <img src="logo.svg" alt="Global Tickets" class="footer-logo">
                <p class="footer-tagline">Your world of live music.<br>Events across Europe and beyond.</p>
                <div class="footer-socials">
                    <a href="#" class="footer-social" aria-label="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                    </a>
                    <a href="#" class="footer-social" aria-label="Twitter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="footer-social" aria-label="TikTok">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Explore -->
            <div class="footer-col">
                <h4 class="footer-col-title">Explore</h4>
                <ul class="footer-links">
                    <li><a href="/GlobalTicket/View/home/home.php">Home</a></li>
                    <li><a href="/GlobalTicket/View/map/map.php">Events map</a></li>
                    <li><a href="/GlobalTicket/View/login/login.php">Log in</a></li>
                    <li><a href="/GlobalTicket/View/signIn/signin.php">Sign up</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-col">
                <h4 class="footer-col-title">Contact</h4>
                <ul class="footer-links">
                    <li><a href="#">ticket@globaltickets.com</a></li>
                    <li><a href="#">+30 111 111 111</a></li>
                    <li><a href="#">@globaltickets</a></li>
                </ul>
            </div>

        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Global Tickets. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Countdown ──
        function updateCountdown() {
            const el = document.getElementById('countdown');
            let [h, m, s] = el.textContent.split(':').map(Number);
            if (--s < 0) { s = 59; if (--m < 0) { m = 59; if (--h < 0) h = m = s = 0; } }
            el.textContent = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }
        setInterval(updateCountdown, 1000);

        // ── Mini map ──
        const miniMap = L.map('home-mini-map', {
            zoomControl: false,
            attributionControl: false,
            scrollWheelZoom: false,
            dragging: true,
            doubleClickZoom: false,
            tap: false
        }).setView([30, 10], 2);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 18,
            subdomains: 'abcd'
        }).addTo(miniMap);

        const pinIcon = L.divIcon({
            className: '',
            html: '<div class="mini-map-pin"></div>',
            iconSize: [14, 14],
            iconAnchor: [7, 7],
            popupAnchor: [0, -10]
        });

        const events = <?= $mapEventsJson ?>;

        const markers = events.map(ev => {
            const imgHtml = ev.image ? `<img src="${ev.image}" class="mini-popup-img">` : '';
            const m = L.marker([ev.lat, ev.lng], { icon: pinIcon }).addTo(miniMap);
            m.bindPopup(`
                ${imgHtml}
                <div class="mini-popup-body">
                    <p class="mini-popup-name">${ev.name}</p>
                    ${ev.artist ? `<p class="mini-popup-sub">${ev.artist}</p>` : ''}
                    <p class="mini-popup-sub">${ev.date} &nbsp;·&nbsp; ${ev.location}</p>
                    <p class="mini-popup-price">${ev.price}</p>
                </div>
            `, { maxWidth: 220, className: 'gt-mini-popup' });
            return m;
        });

        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            miniMap.fitBounds(group.getBounds().pad(0.3));
        }

        setTimeout(() => miniMap.invalidateSize(), 300);

        // ── Scroll animations ──
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    </script>
</body>

</html>