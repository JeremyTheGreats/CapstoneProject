<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resident dashboard – BarangayEcoCare</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/dashboard.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
</head>

<body>
    <?php
      session_start();

      if( !isset($_SESSION['User_id'])){

        header("Location: login.php");
        exit();

      }
    ?>
    <div class="shell">

        <aside class="sidebar">
            <div><a href="#" class="logo">Barangay<b>EcoCare</b></a></div>
            <nav class="nav" aria-label="Dashboard" style="margin-top:20px">
                <a href="#v1" data-i="⌂">Home</a>
                <a href="#v2" data-i="⚲">Report</a>
                <a href="#v3" data-i="▦">Schedule</a>
                <a href="#v4" data-i="★">Rewards</a>
                <a href="#v5" data-i="👤">Profile</a>
            </nav>
            <div class="me">
                <div class="me-info"><span class="avatar">R</span>
                    <div><?php echo $_SESSION['User_fname'], " " , $_SESSION['User_lname'] ?><small>Resident · Purok
                            5</small></div>
                </div>
                <a href="logout.php" class="btn">Log out</a>
            </div>
        </aside>

        <main class="main">
            <div class="views">

                <!-- 1. HOME -->
                <section class="view" data-aos="fade-up" id="v1">
                    <h1>Good day, Juan</h1>
                    <p class="sub">Here is how your barangay is doing today.</p>

                    <article class="card" style="margin-bottom:var(--gap)">
                        <h2>Alerts</h2>
                        <ul class="rows">
                            <li>
                                <div><strong>Next collection: Fri, 6:00 AM</strong><small>Zone 2 · Purok 5. Put your
                                        segregated waste out before 6:00 AM.</small></div>
                            </li>
                            <li>
                                <div><strong>Your report is being handled</strong><small>Illegal dumping · Riverside,
                                        Purok 5</small></div>
                            </li>
                            <li>
                                <div><strong>Report resolved</strong><small>Overflowing bin · Purok 5, near the
                                        chapel</small></div>
                            </li>
                            <li>
                                <div><strong>+50 points added</strong><small>Verified segregation, Purok 5</small></div>
                            </li>
                        </ul>
                        <button class="btn btn-sm" style="margin-top:20px">Turn on phone reminders</button>
                    </article>

                    <div class="grid split" style="margin-bottom:var(--gap)">
                        <article class="card points-hero">
                            <div class="ring" style="--p:64">
                                <div><b>320</b><small>points</small></div>
                            </div>
                            <div><span class="chip">Eco Helper</span>
                                <h2>180 points to a grocery pack</h2>
                                <p class="muted">Segregate properly to keep your streak going.</p>
                            </div>
                        </article>
                        <article class="card">
                            <p class="muted">Next collection</p>
                            <h2 class="blue" style="font-size:1.9rem;margin:4px 0">Fri, 6:00 AM</h2>
                            <p class="muted" style="margin-bottom:16px">Zone 2 · Purok 5</p><a href="#v2"
                                class="btn btn-primary btn-block">Report an issue</a>
                        </article>
                    </div>

                    <div class="grid g3" style="margin-bottom:var(--gap)">
                        <div class="card mini"><b>4 weeks</b><small>Segregation streak</small></div>
                        <div class="card mini"><b>3</b><small>Reports filed</small></div>
                        <div class="card mini"><b>1</b><small>Resolved</small></div>
                    </div>

                    <div class="grid g2">
                        <article class="card">
                            <h2>Recent tickets</h2>
                            <ul class="rows">
                                <li>
                                    <div><strong>Uncollected garbage</strong><small>Purok 5 main road · Oct 6</small>
                                    </div><span class="badge b-pending">Pending</span>
                                </li>
                                <li>
                                    <div><strong>Illegal dumping</strong><small>Riverside, Purok 5 · Oct 3</small></div>
                                    <span class="badge b-progress">In Progress</span>
                                </li>
                                <li>
                                    <div><strong>Overflowing bin</strong><small>Purok 5, near the chapel · Sep
                                            28</small></div><span class="badge b-resolved">Resolved</span>
                                </li>
                            </ul>
                        </article>
                        <article class="card">
                            <h2>Purok 5 standing</h2>
                            <p><span class="big-rank">#1</span> <span class="muted">top purok this October</span></p>
                            <div class="bar" style="margin:10px 0"><i style="width:94%"></i></div>
                            <small class="muted">94% segregation compliance · sample data</small>
                        </article>
                    </div>
                </section>

                <!-- 2. REPORT -->
                <section class="view" data-aos="fade-up" id="v2">
                    <h1>Report an issue</h1>
                    <p class="sub">Tell us what needs cleaning. An official will verify it.</p>
                    <div class="grid g2" style="align-items:start">
                        <form class="card" action="#">
                            <label class="field"><span>What happened?</span>
                                <select required>
                                    <option value="">Choose a concern</option>
                                    <option>Illegal dumping</option>
                                    <option>Uncollected garbage</option>
                                    <option>Overflowing bin</option>
                                    <option>Clogged canal</option>
                                </select></label>
                            <label class="field"><span>Location</span><input
                                    placeholder="e.g. Near the basketball court, Purok 5"></label>
                            <p style="margin:-4px 0 16px"><button type="button" class="btn btn-sm">Use my location
                                    (GPS)</button> <span class="muted" style="margin-left:8px">No GPS pin yet</span></p>
                            <label class="field"><span>Details</span><textarea
                                    placeholder="Describe what you saw"></textarea></label>
                            <label class="field"><span>Photo evidence</span><input type="file" accept="image/*"></label>
                            <button class="btn btn-primary btn-block" type="submit">Send report</button>
                        </form>
                        <article class="card">
                            <h2>My tickets</h2>
                            <ul class="rows">
                                <li>
                                    <div><strong>Uncollected garbage</strong><small>Purok 5 main road · Oct 6</small>
                                    </div><span class="badge b-pending">Pending</span>
                                </li>
                                <li>
                                    <div><strong>Illegal dumping</strong><small>Riverside, Purok 5 · Oct 3</small></div>
                                    <span class="badge b-progress">In Progress</span>
                                </li>
                                <li>
                                    <div><strong>Overflowing bin</strong><small>Purok 5, near the chapel · Sep
                                            28</small></div><span class="badge b-resolved">Resolved</span>
                                </li>
                            </ul>
                        </article>
                    </div>
                </section>

                <!-- 3. SCHEDULE -->
                <section class="view" data-aos="fade-up" id="v3">
                    <h1>Collection schedule</h1>
                    <p class="sub">Your zone is highlighted. Sample schedule; the barangay sets actual times.</p>
                    <div class="grid g3">
                        <article class="card zone"><span class="eyebrow">Zone 1</span>
                            <h3>Purok 1 to 3</h3>
                            <p style="margin:0">Mon and Thu · 6:00 AM</p>
                        </article>
                        <article class="card zone mine"><span class="eyebrow">Zone 2 · Your zone</span>
                            <h3>Purok 4 to 6</h3>
                            <p style="margin:0">Tue and Fri · 6:00 AM</p>
                        </article>
                        <article class="card zone"><span class="eyebrow">Zone 3</span>
                            <h3>Purok 7 to 9</h3>
                            <p style="margin:0">Wed and Sat · 6:00 AM</p>
                        </article>
                    </div>
                </section>

                <!-- 4. REWARDS -->
                <section class="view" data-aos="fade-up" id="v4">
                    <h1>Rewards</h1>
                    <p class="sub">You have <b class="orange">320</b> points. Claim rewards at the barangay hall.</p>
                    <div class="grid g3" style="margin-bottom:32px">
                        <article class="card zone">
                            <h3 style="margin:0">Grocery pack</h3>
                            <p class="price" style="margin:6px 0 16px">500 points</p><button class="btn btn-block"
                                disabled>Need 180 more points</button>
                        </article>
                        <article class="card zone">
                            <h3 style="margin:0">Reusable eco bag</h3>
                            <p class="price" style="margin:6px 0 16px">200 points</p><button
                                class="btn btn-primary btn-block">Redeem</button>
                        </article>
                        <article class="card zone">
                            <h3 style="margin:0">Seedling kit</h3>
                            <p class="price" style="margin:6px 0 16px">150 points</p><button
                                class="btn btn-primary btn-block">Redeem</button>
                        </article>
                    </div>
                    <div class="card hist">
                        <h2>Points history</h2>
                        <ul class="rows">
                            <li>
                                <div><strong>Verified segregation, Purok 5</strong><small>Oct 7</small></div><span
                                    class="plus">+50</span>
                            </li>
                            <li>
                                <div><strong>Recycling drop-off verified (plastic 2 kg)</strong><small>Oct 5</small>
                                </div><span class="plus">+40</span>
                            </li>
                            <li>
                                <div><strong>Verified segregation, Purok 5</strong><small>Sep 30</small></div><span
                                    class="plus">+60</span>
                            </li>
                            <li>
                                <div><strong>Verified segregation, Purok 5</strong><small>Sep 23</small></div><span
                                    class="plus">+80</span>
                            </li>
                            <li>
                                <div><strong>Welcome bonus + first verified segregation</strong><small>Sep 16</small>
                                </div><span class="plus">+90</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- 5. PROFILE -->
                <section class="view" data-aos="fade-up" id="v5">
                    <h1>My profile</h1>
                    <p class="sub">Update the details you gave when you created your account.</p>
                    <div class="grid g2" style="align-items:start">
                        <form class="card" action="#">
                            <h2>Account details</h2>
                            <div class="profile-head"><span class="avatar lg">J</span><button type="button"
                                    class="btn btn-sm">Add/Change photo</button><button type="button"
                                    class="btn btn-sm">Remove</button><small>JPG or PNG. It shows on your
                                    dashboard.</small></div>
                            <label class="field"><span>First name</span><input value="Juan"></label>
                            <label class="field"><span>Last name</span><input value="Dela Cruz"></label>
                            <label class="field"><span>Email</span><input type="email"
                                    value="juan.delacruz@email.com"></label>
                            <label class="field"><span>Mobile or telephone no.</span><input type="tel"
                                    value="09171234567"></label>
                            <label class="field"><span>Purok / Address</span><input value="Purok 5"></label>
                            <button class="btn btn-primary" type="submit">Save changes</button> <button class="btn"
                                type="reset">Undo changes</button>
                        </form>
                        <form class="card" action="#">
                            <h2>Change password</h2>
                            <label class="field"><span>New password</span><input type="password"
                                    placeholder="At least 8 characters" minlength="8"></label>
                            <label class="field"><span>Confirm new password</span><input type="password"
                                    minlength="8"></label>
                            <button class="btn btn-primary" type="submit">Update password</button>
                        </form>
                    </div>
                </section>

            </div>
        </main>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../aos-init.js"></script>
</body>

</html>
