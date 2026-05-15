<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['disco', 'discography'])) {
    header("Location: /GlobalTicket/View/login/login.php");
    exit();
}

$error   = $_GET['error']   ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event — Global Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="addEvent.css">
    <style>
        #map-preview {
            width: 100%;
            height: 200px;
            background: #eee;
            margin-top: 10px;
            display: none;
            border: 1px solid #ccc;
        }

        .geocode-btn {
            margin-top: 8px;
            padding: 7px 16px;
            background: var(--grey-light);
            border: none;
            font-family: var(--font-body);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .geocode-btn:hover {
            background: var(--black);
            color: var(--white);
        }

        .geocode-status {
            font-family: var(--font-body);
            font-size: 12px;
            color: #555;
            margin-top: 6px;
            min-height: 18px;
        }

        .photo-preview {
            width: 240px;
            height: 170px;
            object-fit: cover;
            display: none;
        }

        .flash-error {
            background: #fdecea;
            color: #c0392b;
            padding: 12px 18px;
            font-family: var(--font-body);
            font-size: 13px;
            margin-bottom: 24px;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .field-row { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <input type="checkbox" id="sidebar-toggle">
    <label class="sidebar-overlay" for="sidebar-toggle"></label>

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
            <a href="/GlobalTicket/View/profile/perfilDisco.php">Profile</a>
            <a href="/GlobalTicket/Controller/logout.php">Log out</a>
        </nav>
    </aside>

    <header class="header">
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

    <main class="addevent-main">
        <div class="addevent-container">

            <h1 class="addevent-title">Add event</h1>

            <?php if ($error === 'empty'): ?>
                <div class="flash-error">Please fill in all required fields.</div>
            <?php elseif ($error === 'db_error'): ?>
                <div class="flash-error">Database error. Please try again.</div>
            <?php endif; ?>

            <form class="addevent-form" action="/GlobalTicket/Controller/eventController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="createEvent">
                <input type="hidden" name="latitude"  id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="addevent-layout">

                    <div class="addevent-fields">

                        <div class="field-group">
                            <label class="field-label" for="name">Event name *</label>
                            <input class="field-input" type="text" id="name" name="name" required placeholder=" ">
                            <span class="field-error">Please provide a name</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="artist">Artist / Performer</label>
                            <input class="field-input" type="text" id="artist" name="artist" placeholder=" ">
                        </div>

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label" for="date">Date & time *</label>
                                <input class="field-input" type="datetime-local" id="date" name="date" required>
                                <span class="field-error">Please provide a date</span>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="price">Price (€)</label>
                                <input class="field-input" type="number" id="price" name="price" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="capacity">Capacity (tickets)</label>
                            <input class="field-input" type="number" id="capacity" name="capacity" min="1" placeholder="100">
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="location">Location *</label>
                            <input class="field-input" type="text" id="location" name="location" required placeholder=" ">
                            <span class="field-error">Please enter a location</span>
                            <button type="button" class="geocode-btn" id="geocode-btn">Find on map</button>
                            <span class="geocode-status" id="geocode-status"></span>
                            <div id="map-preview"></div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="description">Description</label>
                            <textarea class="field-textarea" id="description" name="description" placeholder=" " style="height:140px"></textarea>
                        </div>

                    </div>

                    <div class="addevent-photo">
                        <label class="photo-upload" for="photo-input" id="photo-label">
                            <span class="photo-plus" id="photo-plus">+</span>
                            <img class="photo-preview" id="photo-preview" src="" alt="Preview">
                            <input type="file" id="photo-input" name="image" accept="image/*" hidden>
                        </label>
                        <p class="photo-label">Add photo</p>
                    </div>

                </div>

                <button type="submit" class="save-btn">Save event</button>

            </form>

        </div>
    </main>

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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Image preview ──
        document.getElementById('photo-input').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('photo-preview');
                const plus    = document.getElementById('photo-plus');
                preview.src   = e.target.result;
                preview.style.display = 'block';
                plus.style.display    = 'none';
            };
            reader.readAsDataURL(file);
        });

        // ── Geocoding (Nominatim) ──
        let mapInstance = null;
        let mapMarker   = null;

        document.getElementById('geocode-btn').addEventListener('click', async function () {
            const location = document.getElementById('location').value.trim();
            const status   = document.getElementById('geocode-status');

            if (!location) {
                status.textContent = 'Please enter a location first.';
                return;
            }

            status.textContent = 'Searching…';

            try {
                const url  = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(location)}`;
                const resp = await fetch(url, { headers: { 'Accept-Language': 'en' } });
                const data = await resp.json();

                if (!data.length) {
                    status.textContent = 'Location not found. Try a more specific address.';
                    return;
                }

                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);

                document.getElementById('latitude').value  = lat;
                document.getElementById('longitude').value = lng;

                status.textContent = `Found: ${data[0].display_name.slice(0, 80)}…`;

                const mapEl = document.getElementById('map-preview');
                mapEl.style.display = 'block';

                if (!mapInstance) {
                    mapInstance = L.map('map-preview').setView([lat, lng], 14);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(mapInstance);
                    mapMarker = L.marker([lat, lng]).addTo(mapInstance);
                } else {
                    mapInstance.setView([lat, lng], 14);
                    mapMarker.setLatLng([lat, lng]);
                }

                setTimeout(() => mapInstance.invalidateSize(), 100);

            } catch (err) {
                status.textContent = 'Error searching location. Check your connection.';
            }
        });
    </script>

</body>
</html>
