<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BarangayEcoCare – Clean Barangay, Green Future</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css"
        integrity="sha512-x9WwyMYBnlXMNQ6kQ/Lyzu1NqIhLQKL5Oq6xByfXuRj7s9CskyCbLv/1IjqzJmXwFXWr0ov6jBV7Qbc0hh9nHg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/landing.css">
</head>

<body>


    <header class="site-header" data-aos="fade-up">
        <div class="wrap">
            <a href="#top" class="logo">Barangay<b>EcoCare</b></a>
            <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-label="Toggle menu">
            <label for="nav-toggle" class="nav-burger" aria-hidden="true">☰</label>
            <nav class="site-nav" aria-label="Main">
                <a href="#top">Home</a><a href="#features">Features</a><a href="#schedule">Schedule</a>
                <a href="#rewards">Rewards</a><a href="#leaderboard">Leaderboard</a>
                <a href="#how">How it works</a><a href="#roles">Who it's for</a>
                <a href="src/login.php" class="btn btn-primary btn-sm">Log In</a>
            </nav>
        </div>
    </header>

    <main id="top">
        <section class="hero" data-aos="fade-down">
            <div class="wrap">
                <div>
                    <h1>Together for a cleaner, greener, healthier barangay</h1>
                    <p class="lead">BarangayEcoCare is a digital platform that connects our community in keeping the
                        barangay clean and green through proper waste segregation, reporting, monitoring, and rewards.
                    </p>
                    <div class="actions">
                        <a href="src/signup.php" class="btn btn-primary">Get started</a>
                        <a href="#how" class="btn">See how it works</a>
                    </div>
                </div>
                <aside class="overview" aria-label="Sample overview">
                    <div class="overview-head"><strong>Barangay overview</strong><span class="muted">Sample data</span>
                    </div>
                    <div class="overview-stats">
                        <div><b>128</b>Reports</div>
                        <div><b>94</b>Resolved</div>
                    </div>
                    <ul>
                        <li>Overflowing bin, Purok 3 <span class="badge b-pending">Pending</span></li>
                        <li>Illegal dumping, Riverside <span class="badge b-progress">In Progress</span></li>
                    </ul>
                </aside>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="section" id="features" data-aos="zoom-in">
            <div class="wrap">
                <h2>Everything your barangay needs to keep clean</h2>
                <p class="sub">One platform for residents and officials to report, act, and track progress together.</p>
                <div class="grid g4">
                    <article class="card" data-aos="zoom-in">
                        <i class="fa-solid fa-trash"></i>
                        <h3>Report incidents</h3>
                        <p class="muted">Report illegal dumping, uncollected garbage, or overflowing bins with photos
                            and location.</p>
                    </article>
                    <article class="card" data-aos="zoom-in">
                        <i class="fa-solid fa-calendar"></i>
                        <h3>Collection schedules</h3>
                        <p class="muted">Check waste collection schedules for your zone or purok, logged by barangay
                            staff.</p>
                    </article>
                    <article class="card" data-aos="zoom-in">
                        <i class="fa-solid fa-star"></i>
                        <h3>Earn and redeem rewards</h3>
                        <p class="muted">Earn points for verified segregation and redeem them for barangay incentives.
                        </p>
                    </article>
                    <article class="card" data-aos="zoom-in">
                        <i class="fa-solid fa-chart-simple"></i>
                        <h3>Monitor progress</h3>
                        <p class="muted">Track reports and purok segregation compliance with basic analytics.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- SCHEDULE -->
        <section class="section alt" id="schedule" data-aos="zoom-out">
            <div class="wrap">
                <h2>Waste collection schedule</h2>
                <p class="sub">Know when collection comes to your zone. Barangay staff log each completed route.</p>
                <div class="grid g3">
                    <article class="card"><span class="eyebrow">Zone 1</span>
                        <h3>Purok 1 to 3</h3>
                        <p class="muted">Mon and Thu · 6:00 AM</p>
                    </article>
                    <article class="card"><span class="eyebrow">Zone 2</span>
                        <h3>Purok 4 to 6</h3>
                        <p class="muted">Tue and Fri · 6:00 AM</p>
                    </article>
                    <article class="card"><span class="eyebrow">Zone 3</span>
                        <h3>Purok 7 to 9</h3>
                        <p class="muted">Wed and Sat · 6:00 AM</p>
                    </article>
                </div>
                <p class="note">Sample schedule. Actual days and times are set by the barangay.</p>
            </div>
        </section>

        <!-- REWARDS -->
        <section class="section" id="rewards" data-aos="zoom-out">
            <div class="wrap">
                <h2>Redeem your points</h2>
                <p class="sub">Points are awarded after proper segregation or recycling is verified. Redeem them for
                    incentives provided by the barangay.</p>
                <div class="grid g3">
                    <article class="card">
                        <i class="fa-solid fa-basket-shopping"></i>

                        <h3>Grocery pack</h3>
                        <p class="price">500 points</p>
                    </article>
                    <article class="card">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <h3>Reusable eco bag</h3>
                        <p class="price">200 points</p>
                    </article>
                    <article class="card">
                        <i class="fa-solid fa-seedling"></i>
                        <h3>Seedling kit</h3>
                        <p class="price">150 points</p>
                    </article>
                </div>
                <p class="note">Sample incentives. Rewards are point-based only, with no cash or e-wallet transfers.</p>
            </div>
        </section>

        <!-- LEADERBOARD -->
        <section class="section alt center" id="leaderboard" data-aos="fade-up">
            <div class="wrap">
                <h2>Top 3 outstanding puroks</h2>
                <p class="sub">October 2026 · ranked by cleanliness and segregation compliance rate within the barangay.
                </p>
                <div class="podium">
                    <article class="card">
                        <div class="rank">2</div>
                        <h3>Purok 2</h3>
                        <div class="pct">89%</div><small class="muted">segregation compliance</small>
                        <div class="bar" style="margin-top:12px"><i style="width:88%"></i></div>
                        <div class="meta"><span>Cleanliness 88%</span><span>41 resolved</span></div>
                    </article>
                    <article class="card first">
                        <div class="rank">1</div>
                        <h3>Purok 5</h3>
                        <div class="pct">94%</div><small class="muted">segregation compliance</small>
                        <div class="bar" style="margin-top:12px"><i style="width:92%"></i></div>
                        <div class="meta"><span>Cleanliness 92%</span><span>52 resolved</span></div>
                    </article>
                    <article class="card">
                        <div class="rank r3">3</div>
                        <h3>Purok 7</h3>
                        <div class="pct">85%</div><small class="muted">segregation compliance</small>
                        <div class="bar" style="margin-top:12px"><i style="width:86%"></i></div>
                        <div class="meta"><span>Cleanliness 86%</span><span>37 resolved</span></div>
                    </article>
                </div>
                <p class="note">Sample data. Rankings will update from real purok activity.</p>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="section" id="how" data-aos="fade-right">
            <div class="wrap">
                <h2>How it works</h2>
                <p class="sub">From a single report to a cleaner community.</p>
                <ol class="grid g4 steps" style="list-style:none;padding:0;margin:0">
                    <li class="features"><span class="eyebrow">Step 1</span>
                        <h3>Report an issue</h3>
                        <p>Submit a waste concern with a photo and location details.</p>
                    </li>
                    <li class="features"><span class="eyebrow">Step 2</span>
                        <h3>Track the ticket</h3>
                        <p>Follow the status: Pending, In Progress, or Resolved.</p>
                    </li>
                    <li class="features"><span class="eyebrow">Step 3</span>
                        <h3>Segregate and earn</h3>
                        <p>Earn points once proper segregation or recycling is verified.</p>
                    </li>
                    <li class="features"><span class="eyebrow">Step 4</span>
                        <h3>Redeem rewards</h3>
                        <p>Exchange your points for incentives provided by the barangay.</p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- ROLES -->
        <section class="section alt" id="roles" data-aos="zoom-out">
            <div class="wrap">
                <h2>Built for every role</h2>
                <p class="sub">Choose your role when you log in.</p>
                <div class="grid g3">
                    <article class="card">

                        <i class="fa-solid fa-users"></i>
                        <h3>Resident</h3>
                        <p class="muted">Report waste concerns with photos, track tickets, and earn points.</p>
                    </article>
                    <article class="card">
                        <i class="fa-regular fa-user"></i>
                        <h3>Barangay Staff</h3>
                        <p class="muted">Verify reports, assign tickets, log collections, and process redemptions.</p>
                    </article>
                    <article class="card">
                        <i class="fa-solid fa-user-tie"></i>
                        <h3>Administrator</h3>
                        <p class="muted">Oversee environmental incident data and compliance across the barangay.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <span class="logo">Barangay<b>EcoCare</b></span>
        <p>Clean Barangay · Green Future</p>
        <p style="margin-top:22px">© 2026 BarangayEcoCare. All rights reserved.</p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="aos-init.js"></script>
</body>

</html>
