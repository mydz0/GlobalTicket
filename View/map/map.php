<?php
session_start();
require_once '../../Model/db.php';
$db = Database::getInstance()->getConexion();

// Fetch all events that have coordinates
$stmt = $db->query(
    "SELECT id, name, date, location, artist, price, image, latitude, longitude
     FROM events
     WHERE latitude IS NOT NULL AND longitude IS NOT NULL
     ORDER BY date ASC"
);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// All events (for the list panel, even without coords)
$stmtAll = $db->query(
    "SELECT id, name, date, location, artist, price, image, latitude, longitude
     FROM events
     ORDER BY date ASC"
);
$allEvents = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

// Pass events as JSON for Leaflet
$eventsJson = json_encode(array_map(function ($e) {
    return [
        'id'       => $e['id'],
        'name'     => $e['name'],
        'date'     => date('d M Y, H:i', strtotime($e['date'])),
        'location' => $e['location'],
        'artist'   => $e['artist'] ?? '',
        'price'    => $e['price'] > 0 ? '€' . number_format($e['price'], 2) : 'Free',
        'image'    => $e['image'] ? '/GlobalTicket/uploads/events/' . $e['image'] : '',
        'lat'      => (float)$e['latitude'],
        'lng'      => (float)$e['longitude'],
    ];
}, $events));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Map — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="map.css">
</head>

<body>

    <input type="checkbox" id="sidebar-toggle">
    <label class="sidebar-overlay" for="sidebar-toggle"></label>

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
                <?php if ($_SESSION['role'] === 'user'): ?>
                    <a href="/GlobalTicket/View/profile/perfilUser.php">Profile</a>
                <?php else: ?>
                    <a href="/GlobalTicket/View/profile/perfilDisco.php">Profile</a>
                <?php endif; ?>
                <a href="/GlobalTicket/Controller/logout.php">Log out</a>
            <?php else: ?>
                <a href="/GlobalTicket/View/signIn/signin.php">Sign in</a>
                <a href="/GlobalTicket/View/login/login.php">Log in</a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- ── HEADER ── -->
    <header class="header map-header">
        <a href="../home/home.php" class="logo">
            <img src="../home/logo.svg" alt="Global Tickets" class="logo-img">
        </a>
        <label class="menu-btn" for="sidebar-toggle" aria-label="Open menu">
            <svg width="22" height="16" viewBox="0 0 22 16" fill="none">
                <line x1="0" y1="1" x2="22" y2="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="8" x2="22" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <line x1="0" y1="15" x2="22" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </label>
    </header>

    <!-- ── PAGE LAYOUT ── -->
    <div class="map-page">

        <!-- Left panel: event list -->
        <aside class="map-panel">
            <h2 class="map-panel-title">Events</h2>
            <div class="map-panel-search">
                <input type="text" id="panel-search" placeholder="Search events…" class="panel-search-input">
            </div>
            <ul class="map-event-list" id="map-event-list">
                <?php if (empty($allEvents)): ?>
                    <li class="map-event-item map-event-empty">No events found.</li>
                <?php else: ?>
                    <?php foreach ($allEvents as $ev): ?>
                        <li class="map-event-item <?= (!$ev['latitude']) ? 'no-coords' : '' ?>"
                            data-id="<?= $ev['id'] ?>"
                            data-lat="<?= $ev['latitude'] ?? '' ?>"
                            data-lng="<?= $ev['longitude'] ?? '' ?>"
                            data-name="<?= htmlspecialchars(strtolower($ev['name'] . ' ' . ($ev['artist'] ?? '') . ' ' . $ev['location'])) ?>">
                            <div class="map-event-thumb">
                                <?php if (!empty($ev['image'])): ?>
                                    <img src="/GlobalTicket/uploads/events/<?= htmlspecialchars($ev['image']) ?>" alt="">
                                <?php else: ?>
                                    <div class="thumb-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            <div class="map-event-info">
                                <p class="map-event-name"><?= htmlspecialchars($ev['name']) ?></p>
                                <?php if (!empty($ev['artist'])): ?>
                                    <p class="map-event-artist"><?= htmlspecialchars($ev['artist']) ?></p>
                                <?php endif; ?>
                                <p class="map-event-date"><?= htmlspecialchars(date('d M Y', strtotime($ev['date']))) ?></p>
                                <p class="map-event-loc"><?= htmlspecialchars($ev['location']) ?></p>
                            </div>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                                <form method="post" action="/GlobalTicket/Controller/eventController.php" class="reserve-form">
                                    <input type="hidden" name="action" value="createReservation">
                                    <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                                    <button type="submit" class="reserve-btn">Reserve</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </aside>

        <!-- Right: Leaflet map -->
        <div id="main-map"></div>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Map initialise ──
        const map = L.map('main-map').setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(map);

        // Custom marker icon
        const pinIcon = L.divIcon({
            className: '',
            html: '<div class="map-pin"></div>',
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -28]
        });

        // ── Events data from PHP ──
        const events = <?= $eventsJson ?>;

        const markers = {};

        events.forEach(ev => {
            const imgHtml = ev.image
                ? `<img src="${ev.image}" alt="${ev.name}" class="popup-img">`
                : '';

            const popup = L.popup({ maxWidth: 260, className: 'gt-popup' }).setContent(`
                ${imgHtml}
                <div class="popup-body">
                    <p class="popup-name">${ev.name}</p>
                    ${ev.artist ? `<p class="popup-artist">${ev.artist}</p>` : ''}
                    <p class="popup-meta">${ev.date}</p>
                    <p class="popup-meta">${ev.location}</p>
                    <p class="popup-price">${ev.price}</p>
                </div>
            `);

            const marker = L.marker([ev.lat, ev.lng], { icon: pinIcon })
                .addTo(map)
                .bindPopup(popup);

            markers[ev.id] = marker;
        });

        // ── Fit map to all markers if any ──
        if (events.length > 0) {
            const group = L.featureGroup(Object.values(markers));
            map.fitBounds(group.getBounds().pad(0.2));
        }

        // ── Panel item click → fly to marker ──
        document.querySelectorAll('.map-event-item').forEach(item => {
            item.addEventListener('click', function (e) {
                if (e.target.classList.contains('reserve-btn')) return;
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                const id  = parseInt(this.dataset.id);
                if (!lat || !lng) return;
                map.flyTo([lat, lng], 13, { duration: 1 });
                if (markers[id]) markers[id].openPopup();
                document.querySelectorAll('.map-event-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // ── Search filter ──
        document.getElementById('panel-search').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.map-event-item').forEach(item => {
                const text = item.dataset.name || '';
                item.style.display = text.includes(q) ? '' : 'none';
            });
        });
    </script>

</body>
</html>
