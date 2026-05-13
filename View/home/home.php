<?php session_start(); ?>
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
    <link rel="stylesheet" href="home.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <style>
        #cookie-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 490;
        }
        #cookie-banner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 480px;
            background: #fff;
            padding: 40px 40px 32px;
            z-index: 500;
            box-shadow: 0 8px 48px rgba(0,0,0,0.18);
            flex-direction: column;
            font-family: 'DM Sans', sans-serif;
        }
        .cookie-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #000;
        }
        #cookie-banner p {
            font-size: 13px;
            color: #444;
            line-height: 1.6;
            margin: 0;
        }
        .cookie-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .cookie-btn {
            flex: 1;
            padding: 12px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid #000;
            transition: background 0.15s, color 0.15s;
        }
        .cookie-btn-accept {
            background: #000;
            color: #fff;
        }
        .cookie-btn-accept:hover {
            background: #ff2222;
            border-color: #ff2222;
        }
        .cookie-btn-reject {
            background: #fff;
            color: #000;
        }
        .cookie-btn-reject:hover {
            background: #f0f0f0;
        }
    </style>
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
    </section>

    <!-- ── MAIN GRID ── -->
    <main class="container">
        <div class="grid">

            <a class="card" href="../event/event.php">
                <img src="wte.jpg" alt="Wave to Earth">
                <div class="card-info">
                    <p class="card-desc">Lorem ipsum dolor sit amet</p>
                    <h3>Wave to Earth</h3>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <img src="rusowsky.webp" alt="Schoolgirl byebye">
                <div class="card-info">
                    <p class="card-desc">Lorem ipsum dolor sit amet</p>
                    <h3>Schoolgirl byebye</h3>
                </div>
            </a>

            <!-- Tall ad – spans 3 rows -->
            <div class="card ad-tall">
                <img src="ad.jpg" alt="Ad">
            </div>

            <a class="card" href="../event/event.php">
                <img src="frankOcean.jpg" alt="Frank Ocean">
                <div class="card-info">
                    <p class="card-desc">Lorem ipsum dolor sit amet</p>
                    <h3>Frank Ocean</h3>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <img src="kaliUchis.webp" alt="Kali Uchis">
                <div class="card-info">
                    <p class="card-desc">Lorem ipsum dolor sit amet</p>
                    <h3>Kali Uchis</h3>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <img src="pinkpantheress2.webp" alt="PinkPanthress">
                <div class="card-info">
                    <p class="card-desc">Lorem ipsum dolor sit amet</p>
                    <h3>PinkPanthress</h3>
                </div>
            </a>

            <a class="card" href="../event/event.php">
                <img src="charliXCX.webp" alt="Charli XCX">
                <div class="card-info">
                    <p class="card-desc">Lorem ipsum dolor sit amet</p>
                    <h3>Charli XCX</h3>
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

    <!-- ── FOOTER ── -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo-wrap">
                <img src="logo.svg" alt="Global Tickets" class="logo-img">
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

    <!-- ── COOKIE OVERLAY ── -->
    <div id="cookie-overlay"></div>

    <!-- ── COOKIE BANNER ── -->
    <div id="cookie-banner">
        <h2 class="cookie-title">Cookie Policy</h2>
        <p>We use cookies to improve your experience on our site. Do you accept the use of cookies?</p>
        <div class="cookie-actions">
            <button id="btn-accept" class="cookie-btn cookie-btn-accept">Accept</button>
            <button id="btn-reject" class="cookie-btn cookie-btn-reject">Reject</button>
        </div>
    </div>

    <!-- ── REVIEW COOKIES BUTTON ── -->
    <button id="btn-review">Cookie settings</button>

    <script src="home.js"></script>
</body>

</html>