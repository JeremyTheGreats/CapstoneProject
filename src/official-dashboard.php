<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Official workspace – BarangayEcoCare</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../style/global.css">
      <link rel="stylesheet" href="../style/dashboard.css">
      <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
</head>

<body>
    <?php
    session_start();
    
    if ( !isset($_SESSION['User_id'])){

      header("Location: login.php");
      exit();
    }
  
  ?>


    <div class="shell">

        <!-- SIDEBAR (becomes a scrolling top bar on mobile) -->
        <aside class="sidebar">
            <div>
                <a href="#" class="logo">Barangay<b>EcoCare</b></a>
                <p class="ws">Official workspace</p>
            </div>
            <!-- Views switch via URL hash (#v1..#v6) – no JavaScript needed -->
            <nav class="nav" aria-label="Dashboard">
                <a href="#v1" data-i="⌂">Overview</a>
                <a href="#v2" data-i="☑">Tickets <span class="count">2</span></a>
                <a href="#v3" data-i="🗑">Collections</a>
                <a href="#v4" data-i="✔">Segregation <span class="count">3</span></a>
                <a href="#v5" data-i="▦">Redemptions <span class="count">3</span></a>
                <a href="#v6" data-i="👤">Profile</a>
            </nav>
            <div class="me">
                <div class="me-info"><span class="avatar">M</span>
                    <div><?php echo $_SESSION['User_fname'], "" , $_SESSION['User_lname'] ?><small>Barangay
                            Official</small></div>
                </div>
                <a href="logout.php" class="btn">Log out</a>
            </div>
        </aside>

        <main class="main">
            <div class="views">

                <!-- 1. OVERVIEW (default view = first child) -->
                <section class="view" data-aos="fade-up" id="v1">
                    <h1>Good day, <?php echo $_SESSION['User_fname'] ?> </h1>
                    <p class="sub">Here is what needs your attention today.</p>
                    <div class="grid g4" style="margin-bottom:var(--gap)">
                        <div class="card stat"><small>Need verification</small><b class="o">2</b></div>
                        <div class="card stat"><small>In progress</small><b>2</b></div>
                        <div class="card stat"><small>Resolved</small><b>1</b></div>
                        <div class="card stat"><small>Redemptions to approve</small><b class="o">3</b></div>
                    </div>
                    <div class="grid g2">
                        <article class="card">
                            <h2>Segregation compliance by purok</h2>
                            <ul class="compliance">
                                <li>
                                    <div><span>Purok 5</span><b>94%</b></div>
                                    <div class="bar"><i style="width:94%"></i></div>
                                </li>
                                <li>
                                    <div><span>Purok 2</span><b>89%</b></div>
                                    <div class="bar"><i style="width:89%"></i></div>
                                </li>
                                <li>
                                    <div><span>Purok 7</span><b>85%</b></div>
                                    <div class="bar"><i style="width:85%"></i></div>
                                </li>
                                <li>
                                    <div><span>Purok 4</span><b>78%</b></div>
                                    <div class="bar"><i style="width:78%"></i></div>
                                </li>
                                <li>
                                    <div><span>Purok 1</span><b>72%</b></div>
                                    <div class="bar"><i style="width:72%"></i></div>
                                </li>
                            </ul>
                            <small class="muted">Sample data</small>
                        </article>
                        <article class="card">
                            <h2>Needs verification</h2>
                            <ul class="rows">
                                <li>
                                    <div><strong>Illegal dumping</strong><small>Riverside, Purok 3</small></div><span
                                        class="badge b-pending">Pending</span>
                                </li>
                                <li>
                                    <div><strong>Overflowing bin</strong><small>Near chapel, Purok 5</small></div><span
                                        class="badge b-pending">Pending</span>
                                </li>
                            </ul>
                            <a href="#v2" class="btn" style="margin-top:20px">Open all tickets</a>
                        </article>
                    </div>
                </section>

                <!-- 2. TICKETS -->
                <section class="view" data-aos="fade-up" id="v2">
                    <h1>Tickets</h1>
                    <p class="sub">Verify reports, assign a crew, and update the status.</p>
                    <div class="tabs"><span class="on">All</span><span>Pending</span><span>In
                            Progress</span><span>Resolved</span>
                    </div>
                    <div class="card">
                        <ul class="rows">
                            <li>
                                <div><strong>Illegal dumping</strong><small>Riverside, Purok 3 · by Ana R. · Today · <a
                                            class="pin" href="#v2">GPS pin</a> · Photo attached</small></div>
                                <div class="dd"><select aria-label="Status">
                                        <option selected>Pending</option>
                                        <option>In Progress</option>
                                        <option>Resolved</option>
                                    </select>
                                    <select aria-label="Crew">
                                        <option selected>Unassigned</option>
                                        <option>Crew A</option>
                                        <option>Crew B</option>
                                        <option>Crew C</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div><strong>Overflowing bin</strong><small>Near chapel, Purok 5 · by Juan D. · Today ·
                                        <a class="pin" href="#v2">GPS pin</a> · Photo attached</small></div>
                                <div class="dd"><select aria-label="Status">
                                        <option selected>Pending</option>
                                        <option>In Progress</option>
                                        <option>Resolved</option>
                                    </select>
                                    <select aria-label="Crew">
                                        <option selected>Unassigned</option>
                                        <option>Crew A</option>
                                        <option>Crew B</option>
                                        <option>Crew C</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div><strong>Uncollected garbage</strong><small>Main road, Purok 7 · by Pedro L. · Oct 6
                                        · <a class="pin" href="#v2">GPS pin</a> · Photo attached</small></div>
                                <div class="dd"><select aria-label="Status">
                                        <option>Pending</option>
                                        <option selected>In Progress</option>
                                        <option>Resolved</option>
                                    </select>
                                    <select aria-label="Crew">
                                        <option>Unassigned</option>
                                        <option>Crew A</option>
                                        <option selected>Crew B</option>
                                        <option>Crew C</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div><strong>Clogged canal</strong><small>Purok 2 drainage · by Liza M. · Oct 5</small>
                                </div>
                                <div class="dd"><select aria-label="Status">
                                        <option>Pending</option>
                                        <option selected>In Progress</option>
                                        <option>Resolved</option>
                                    </select>
                                    <select aria-label="Crew">
                                        <option>Unassigned</option>
                                        <option selected>Crew A</option>
                                        <option>Crew B</option>
                                        <option>Crew C</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div><strong>Overflowing bin</strong><small>Market area, Purok 4 · by Ben C. · Oct
                                        2</small></div>
                                <div class="dd"><select aria-label="Status">
                                        <option>Pending</option>
                                        <option>In Progress</option>
                                        <option selected>Resolved</option>
                                    </select>
                                    <select aria-label="Crew">
                                        <option>Unassigned</option>
                                        <option>Crew A</option>
                                        <option>Crew B</option>
                                        <option selected>Crew C</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- 3. COLLECTIONS -->
                <section class="view" data-aos="fade-up" id="v3">
                    <h1>Collections</h1>
                    <p class="sub">Log each route once it is completed.</p>
                    <div class="grid g3" style="margin-bottom:var(--gap)">
                        <article class="card zone"><span class="eyebrow">Zone 1</span>
                            <h3>Purok 1 to 3</h3>
                            <p>Mon and Thu · 6:00 AM</p><button class="btn btn-primary btn-sm">Log as collected</button>
                        </article>
                        <article class="card zone"><span class="eyebrow">Zone 2</span>
                            <h3>Purok 4 to 6</h3>
                            <p>Tue and Fri · 6:00 AM</p><button class="btn btn-primary btn-sm">Log as collected</button>
                        </article>
                        <article class="card zone"><span class="eyebrow">Zone 3</span>
                            <h3>Purok 7 to 9</h3>
                            <p>Wed and Sat · 6:00 AM</p><button class="btn btn-primary btn-sm">Log as collected</button>
                        </article>
                    </div>
                    <div class="card">
                        <h2>Collection log</h2>
                        <ul class="rows">
                            <li>
                                <div><strong>Zone 3 collected</strong><small>Wed, Oct 7 · 6:40 AM · logged by Maria
                                        Santos</small></div>
                                <span class="badge b-done">Done</span>
                            </li>
                            <li>
                                <div><strong>Zone 2 collected</strong><small>Tue, Oct 6 · 6:25 AM · logged by Maria
                                        Santos</small></div>
                                <span class="badge b-done">Done</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- 4. SEGREGATION -->
                <section class="view" data-aos="fade-up" id="v4">
                    <h1>Segregation and recycling</h1>
                    <p class="sub">Verify what residents submitted. Approving awards the points to their account.</p>
                    <div class="card" style="margin-bottom:var(--gap)">
                        <ul class="rows">
                            <li>
                                <div><strong>Juan Dela Cruz · Purok 5</strong><small>Segregated 4 bins (photo checked) ·
                                        50
                                        points</small></div>
                                <div class="acts"><button class="btn">Reject</button><button
                                        class="btn btn-primary">Approve
                                        +50</button></div>
                            </li>
                            <li>
                                <div><strong>Ana Reyes · Purok 3</strong><small>Recycling drop-off: plastic 2 kg · 40
                                        points</small>
                                </div>
                                <div class="acts"><button class="btn">Reject</button><button
                                        class="btn btn-primary">Approve
                                        +40</button></div>
                            </li>
                            <li>
                                <div><strong>Pedro Lim · Purok 7</strong><small>Segregated 3 bins (photo checked) · 30
                                        points</small>
                                </div>
                                <div class="acts"><button class="btn">Reject</button><button
                                        class="btn btn-primary">Approve
                                        +30</button></div>
                            </li>
                        </ul>
                    </div>
                    <div class="card">
                        <h2>Points awarded</h2>
                        <p class="muted">No points awarded yet in this session.</p>
                    </div>
                </section>

                <!-- 5. REDEMPTIONS -->
                <section class="view" data-aos="fade-up" id="v5">
                    <h1>Redemptions</h1>
                    <p class="sub">Approve after checking the resident's points. Rewards are point-based only.</p>
                    <div class="card">
                        <ul class="rows">
                            <li>
                                <div><strong>Ana Reyes</strong><small>Reusable eco bag · 200 points</small></div>
                                <div class="acts"><button class="btn">Reject</button><button
                                        class="btn btn-primary">Approve</button>
                                </div>
                            </li>
                            <li>
                                <div><strong>Juan Dela Cruz</strong><small>Seedling kit · 150 points</small></div>
                                <div class="acts"><button class="btn">Reject</button><button
                                        class="btn btn-primary">Approve</button>
                                </div>
                            </li>
                            <li>
                                <div><strong>Pedro Lim</strong><small>Grocery pack · 500 points</small></div>
                                <div class="acts"><button class="btn">Reject</button><button
                                        class="btn btn-primary">Approve</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- 6. PROFILE -->
                <section class="view" data-aos="fade-up" id="v6">
                    <h1>My profile</h1>
                    <p class="sub">Update your photo and the details shown on your account.</p>
                    <div class="grid g2" style="align-items:start">
                        <form class="card" action="#">
                            <h2>Account details</h2>
                            <div class="profile-head"><span class="avatar lg">M</span><button type="button"
                                    class="btn btn-sm">Add/Change photo</button><button type="button"
                                    class="btn btn-sm">Remove</button><small>JPG or PNG. It shows on your
                                    dashboard.</small></div>
                            <label class="field"><span>First name</span><input value="Maria"></label>
                            <label class="field"><span>Last name</span><input value="Santos"></label>
                            <label class="field"><span>Position</span><input value="Barangay Official"></label>
                            <label class="field"><span>Email</span><input type="email"
                                    value="maria.santos@barangay.gov.ph"></label>
                            <label class="field"><span>Mobile or telephone no.</span><input type="tel"
                                    value="09181234567"></label>
                            <button class="btn btn-primary" type="submit">Save changes</button> <button class="btn"
                                type="reset">Undo
                                changes</button>
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
