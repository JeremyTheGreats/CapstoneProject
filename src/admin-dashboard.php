<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="logo.png">
    <title>Administrator – BarangayEcoCare</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/dashboard.css">
    <link rel="stylesheet" href="../style/admin-dashboard.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

</head>

<body>

    <?php

    session_start();

    if (isset($_SESSION['User_role']) != 'Admin') {
        header("Location: login.php");
        exit();
    }

    include "../config/db.php";

    $sql = "SELECT ID, fname, lname, email, phone, address, role, status FROM users ORDER BY ID DESC";
    $result = mysqli_query($conn, $sql);

    ?>

    <div class="shell">

        <aside class="sidebar">
            <div>
                <a href="#" class="logo"><img src="../images/spark.png" alt="" width="40"
                        height="40">Barangay<b>EcoCare</b></a>
                <p class="ws">Administrator · full access</p>
            </div>
            <nav class="nav" aria-label="Dashboard">
                <a href="#v1" data-i="⌂">Overview</a>
                <a href="#v2" data-i="✔">Account approvals <span class="count" id="nav-pending">4</span></a>
                <span class="nav-label">System</span>
                <a href="#v6" data-i="▦">Zones &amp; schedule</a>
                <a href="#v7" data-i="★">Rewards &amp; rules</a>
                <a href="#v8" data-i="ıl">Reports</a>
                <a href="#v9" data-i="📋">Audit log</a>
                <a href="#v10" data-i="👤">Profile</a>
            </nav>
            <div class="me">
                <div class="me-info"><span class="avatar">A</span>
                    <div><?php echo $_SESSION['User_fname'], " ", $_SESSION['User_lname']; ?><small>Administrator</small></div>
                </div>
                <a href="logout.php" class="btn">Log out</a>
            </div>
        </aside>

        <main class="main">
            <div class="views">

                <!-- 1. OVERVIEW -->
                <section class="view" data-aos="fade-up" id="v1">
                    <h1>Admin overview</h1>
                    <p class="sub">System-wide activity for the barangay.</p>
                    <div class="grid g4" style="margin-bottom:var(--gap)">
                        <div class="card stat"><small>Registered users</small><b>412</b></div>
                        <div class="card stat"><small>Open tickets</small><b class="o">24</b></div>
                        <div class="card stat"><small>Points awarded (Oct)</small><b>8,640</b></div>
                        <div class="card stat"><small>Accounts awaiting approval</small><b class="o"
                                id="stat-pending">4</b></div>
                    </div>
                    <div class="grid g2" style="margin-bottom:var(--gap)">
                        <article class="card">
                            <h2>Users by role</h2>
                            <ul class="compliance">
                                <li>
                                    <div><span>Residents</span><b>384</b></div>
                                    <div class="bar"><i style="width:93%"></i></div>
                                </li>
                                <li>
                                    <div><span>Garbage collectors</span><b>12</b></div>
                                    <div class="bar"><i style="width:15%"></i></div>
                                </li>
                                <li>
                                    <div><span>Barangay staff</span><b>14</b></div>
                                    <div class="bar"><i style="width:17%"></i></div>
                                </li>
                                <li>
                                    <div><span>Administrators</span><b>2</b></div>
                                    <div class="bar"><i style="width:4%"></i></div>
                                </li>
                            </ul><small class="muted">Sample data</small>
                        </article>
                        <article class="card">
                            <h2>Needs your attention</h2>
                            <ul class="rows">
                                <li>
                                    <div><strong>New account requests</strong><small>Only you can approve or reject
                                            them</small></div><a href="#v2" class="btn btn-primary btn-sm">Review</a>
                                </li>
                                <li>
                                    <div><strong>Zone 1 not collected</strong><small>Truck breakdown · reported by Crew
                                            A</small></div><span class="badge b-pending">Needs attention</span>
                                </li>
                            </ul>
                        </article>
                    </div>
                </section>

                <!-- 2. ACCOUNT APPROVALS -->
                <section class="view" data-aos="fade-up" id="v2">
                    <h1>Account approvals</h1>
                    <p class="sub">Residents, garbage collectors, and barangay staff sign up on their own. Their
                        accounts stay inactive until you approve them.</p>
                    <p class="notice"><b>Admin-only approval.</b> No one else can approve accounts, and invitation links
                        are no longer used. Review each request, set the zone for collectors or permissions for staff,
                        then approve.</p>
                    <div class="ptabs" id="acct-filter" role="group" aria-label="Filter accounts">
                        <button type="button" aria-selected="false" data-f="all">All</button>
                        <button type="button" aria-selected="true" data-f="pending">Pending <span class="ptab-n"
                                id="pending-n">4</span></button>
                        <button type="button" aria-selected="false" data-f="active">Active</button>
                        <button type="button" aria-selected="false" data-f="inactive">Inactive / Rejected</button>
                    </div>

                    <div class="card table-wrap">
                        <table class="table" id="acct-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Purok / Zone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Map database status values to your HTML attributes (pending, active, inactive)
                                        $status = strtolower($row['status'] ?? 'pending');
                                        $role = htmlspecialchars($row['role']);
                                        $fullName = htmlspecialchars($row['fname'] . ' ' . $row['lname']);
                                        $email = htmlspecialchars($row['email']);
                                        $purok = htmlspecialchars($row['purok'] ?? 'Not assigned');
                                        $userId = $row['ID'];
                                        ?>
                                        <tr data-status="<?php echo $status; ?>" data-role="<?php echo $role; ?>"
                                            data-name="<?php echo $fullName; ?>">
                                            <td>
                                                <strong>
                                                    <?php echo $fullName; ?>
                                                </strong><br>
                                                <small class="muted">
                                                    <?php echo $email; ?>
                                                </small>
                                            </td>
                                            <td><span class="badge b-role">
                                                    <?php echo $role; ?>
                                                </span></td>
                                            <td class="loc">
                                                <?php echo $purok; ?>
                                            </td>
                                            <td class="st">
                                                <!-- Status badge indicator -->
                                                <span class="status-badge <?php echo $status; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                    
                                                </span>
                                            </td>

                                            <td class="ac">
                                                <?php if (strtolower($role) === 'admin') { ?>
                                                    <span class="muted" style="font-size: 13px; font-style: italic;">Protected (Admin)</span>
                                                <?php } elseif ($status === 'pending') { ?>
                                                    <div class="action-group">
                                                        <a href="approve_user.php?id=<?php echo $userId; ?>&action=approve"
                                                        class="btn btn-primary btn-sm">Approve</a>
                                                        <a href="approve_user.php?id=<?php echo $userId; ?>&action=reject"
                                                        class="btn btn-red btn-sm">Reject</a>
                                                    </div>
                                                <?php } else { ?>
                                                    <a href="approve_user.php?id=<?php echo $userId; ?>&action=toggle"
                                                    class="btn btn-blue btn-sm">Change Status</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" style="text-align:center; padding: 20px;">No accounts found in the database.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <p class="muted" id="acct-empty" hidden style="padding:16px 12px 0">No accounts in this list.
                        </p>
                    </div>

                    <article class="card" style="margin-top:var(--gap)">
                        <h2>Role guide</h2>
                        <ul class="rows">
                            <li>
                                <div><strong>Resident</strong><small>Reports waste problems, tracks tickets, earns and
                                        redeems points.</small></div><span class="badge b-role">Sign-up · admin
                                    approves</span>
                            </li>
                            <li>
                                <div><strong>Garbage Collector</strong><small>Sees assigned zone, route checklist, and
                                        records observations.</small></div><span class="badge b-role">Sign-up · admin
                                    approves</span>
                            </li>
                            <li>
                                <div><strong>Barangay Staff</strong><small>Verifies reports, tracks collections,
                                        approves redemptions per permissions.</small></div><span
                                    class="badge b-role">Sign-up · admin approves</span>
                            </li>
                            <li>
                                <div><strong>Administrator</strong><small>Approves accounts, manages roles, rules, and
                                        has full access to all dashboards.</small></div><span
                                    class="badge b-pending">Restricted</span>
                            </li>
                        </ul>
                    </article>
                </section>

                <!-- 6. ZONES & SCHEDULE -->
                <section class="view" data-aos="fade-up" id="v6">
                    <h1>Zones &amp; schedule</h1>
                    <p class="sub">Set the collection days, time, and crew for each zone.</p>
                    <div class="grid g3">
                        <form class="card zone" action="#"><span class="eyebrow">Zone 1</span>
                            <h3>Purok 1 to 3</h3>
                            <label class="field"><span>Days</span><input value="Mon and Thu"></label>
                            <label class="field"><span>Time</span><input type="time" value="06:00"></label>
                            <label class="field"><span>Crew</span><select>
                                    <option selected>Crew A</option>
                                    <option>Crew B</option>
                                    <option>Crew C</option>
                                </select></label>
                            <button class="btn btn-primary btn-sm">Save</button>
                        </form>
                        <form class="card zone" action="#"><span class="eyebrow">Zone 2</span>
                            <h3>Purok 4 to 6</h3>
                            <label class="field"><span>Days</span><input value="Tue and Fri"></label>
                            <label class="field"><span>Time</span><input type="time" value="06:00"></label>
                            <label class="field"><span>Crew</span><select>
                                    <option>Crew A</option>
                                    <option selected>Crew B</option>
                                    <option>Crew C</option>
                                </select></label>
                            <button class="btn btn-primary btn-sm">Save</button>
                        </form>
                        <form class="card zone" action="#"><span class="eyebrow">Zone 3</span>
                            <h3>Purok 7 to 9</h3>
                            <label class="field"><span>Days</span><input value="Wed and Sat"></label>
                            <label class="field"><span>Time</span><input type="time" value="06:00"></label>
                            <label class="field"><span>Crew</span><select>
                                    <option>Crew A</option>
                                    <option>Crew B</option>
                                    <option selected>Crew C</option>
                                </select></label>
                            <button class="btn btn-primary btn-sm">Save</button>
                        </form>
                    </div>
                </section>

                <!-- 7. REWARDS & RULES -->
                <section class="view" data-aos="fade-up" id="v7">
                    <h1>Rewards &amp; rules</h1>
                    <p class="sub">Manage incentives, point values, and badges. Rewards are point-based only.</p>
                    <div class="toolbar"><button class="btn btn-primary">Add reward</button></div>
                    <div class="card table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Reward</th>
                                    <th>Points</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Grocery pack</strong></td>
                                    <td class="big-num" style="font-size:1.1rem">500</td>
                                    <td>20</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button><button
                                                class="btn btn-sm">Disable</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Reusable eco bag</strong></td>
                                    <td class="big-num" style="font-size:1.1rem">200</td>
                                    <td>85</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button><button
                                                class="btn btn-sm">Disable</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Seedling kit</strong></td>
                                    <td class="big-num" style="font-size:1.1rem">150</td>
                                    <td>40</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button><button
                                                class="btn btn-sm">Disable</button></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <article class="card" style="margin-top:var(--gap)">
                        <h2>Community points (purok-wide)</h2>
                        <p class="notice">On each collection schedule: perfect purok earns the top amount, the purok
                            closest to perfect earns the middle amount, and the rest earn the base amount, per resident.
                        </p>
                        <form action="#">
                            <div class="grid g3">
                                <label class="field"><span>Perfect purok (points each)</span><input type="number"
                                        min="0" value="15"></label>
                                <label class="field"><span>Closest to perfect (points each)</span><input type="number"
                                        min="0" value="10"></label>
                                <label class="field"><span>All other puroks (points each)</span><input type="number"
                                        min="0" value="5"></label>
                            </div>
                            <button class="btn btn-primary" type="submit">Save community rules</button>
                        </form>
                    </article>
                    <article class="card" style="margin-top:var(--gap)">
                        <h2>Report rewards (individual)</h2>
                        <p class="notice">Points are given only after Barangay Staff verify the report.</p>
                        <form action="#">
                            <div class="grid g3">
                                <label class="field"><span>Illegal dumping (points)</span><input type="number" min="0"
                                        value="15"></label>
                                <label class="field"><span>Overflowing garbage (points)</span><input type="number"
                                        min="0" value="15"></label>
                                <label class="field"><span>Duplicate window (hours, same location)</span><input
                                        type="number" min="0" value="48"></label>
                                <label class="field"><span>Welcome bonus (one time, first sign-in)</span><input
                                        type="number" min="0" value="20"></label>
                            </div>
                            <div class="toolbar"><label class="check"><input type="checkbox" checked disabled> No points
                                    for pending or rejected reports</label><label class="check"><input type="checkbox"
                                        checked> Block duplicate reports of the same incident</label><label
                                    class="check"><input type="checkbox" checked> Record every staff and admin action in
                                    the audit log</label></div>
                            <button class="btn btn-primary" type="submit">Save report rules</button>
                        </form>
                    </article>
                    <article class="card table-wrap" style="margin-top:var(--gap)">
                        <h2>Badges</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Badge</th>
                                    <th>Requirement</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Eco Helper</strong></td>
                                    <td>Reach 100 points</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>First Report</strong></td>
                                    <td>1 verified report</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>4-week Streak</strong></td>
                                    <td>Segregate 4 weeks in a row</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Community Hero</strong></td>
                                    <td>5 community rewards</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Top Reporter</strong></td>
                                    <td>10 verified reports</td>
                                    <td><span class="badge b-done">Active</span></td>
                                    <td>
                                        <div class="acts"><button class="btn btn-sm">Edit</button></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table><button class="btn btn-primary" style="margin-top:16px">Add badge</button>
                    </article>
                </section>

                <!-- 8. REPORTS -->
                <section class="view" data-aos="fade-up" id="v8">
                    <h1>Reports</h1>
                    <p class="sub">Compliance and ticket analytics.</p>
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
                            </ul><small class="muted">Sample data</small>
                        </article>
                        <article class="card">
                            <h2>Tickets by status</h2>
                            <ul class="compliance">
                                <li>
                                    <div><span>Pending</span><b>9</b></div>
                                    <div class="bar"><i style="width:30%"></i></div>
                                </li>
                                <li>
                                    <div><span>In Progress</span><b>15</b></div>
                                    <div class="bar"><i style="width:50%"></i></div>
                                </li>
                                <li>
                                    <div><span>Resolved</span><b>94</b></div>
                                    <div class="bar"><i style="width:100%"></i></div>
                                </li>
                            </ul>
                            <button class="btn" style="margin-top:8px">Export CSV</button>
                        </article>
                    </div>
                </section>

                <!-- 9. AUDIT LOG -->
                <section class="view" data-aos="fade-up" id="v9">
                    <h1>Audit log</h1>
                    <p class="sub">Every staff and admin action that affects points, accounts, or rules.</p>
                    <div class="toolbar"><input type="search" placeholder="Search by person or action"><button
                            class="btn">Export CSV</button></div>
                    <div class="card table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>When</th>
                                    <th>Who</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Oct 8 · 8:10 AM</td>
                                    <td>Admin Cruz<br><small class="muted">Admin</small></td>
                                    <td>Approved account</td>
                                    <td>Juan Dela Cruz · Resident</td>
                                </tr>
                                <tr>
                                    <td>Oct 7 · 9:12 AM</td>
                                    <td>Maria Santos<br><small class="muted">Staff</small></td>
                                    <td>Verified report +15</td>
                                    <td>Illegal dumping, Riverside, Purok 3 (Ana R.)</td>
                                </tr>
                                <tr>
                                    <td>Oct 7 · 8:40 AM</td>
                                    <td>Maria Santos<br><small class="muted">Staff</small></td>
                                    <td>Reviewed collector report</td>
                                    <td>Purok 4, Market area, 8 of 8 bins</td>
                                </tr>
                                <tr>
                                    <td>Oct 6 · 4:05 PM</td>
                                    <td>Admin Cruz<br><small class="muted">Admin</small></td>
                                    <td>Changed rule</td>
                                    <td>Community points per resident: 8 to 10</td>
                                </tr>
                                <tr>
                                    <td>Oct 6 · 2:30 PM</td>
                                    <td>Maria Santos<br><small class="muted">Staff</small></td>
                                    <td>Marked as duplicate</td>
                                    <td>Overflowing garbage, Purok 5 (no points)</td>
                                </tr>
                                <tr>
                                    <td>Oct 5 · 6:50 AM</td>
                                    <td>Rico Dela Peña<br><small class="muted">Collector</small></td>
                                    <td>Submitted observation</td>
                                    <td>Purok 5, Near chapel, 5 of 6 bins, evidence attached</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="audit-new"></div>
                    </div>
                </section>

                <!-- 10. PROFILE -->
                <section class="view" data-aos="fade-up" id="v10">
                    <h1>My profile</h1>
                    <p class="sub">Update your photo and the details shown on your account.</p>
                    <div class="grid g2" style="align-items:start">
                        <form class="card" action="#">
                            <h2>Account details</h2>
                            <div class="profile-head"><span class="avatar lg">A</span><button type="button"
                                    class="btn btn-sm">Add/Change photo</button><button type="button"
                                    class="btn btn-sm">Remove</button><small>JPG or PNG.</small></div>
                            <label class="field"><span>First name</span><input value="Admin"></label>
                            <label class="field"><span>Last name</span><input value="Cruz"></label>
                            <label class="field"><span>Email</span><input type="email"
                                    value="admin@barangay.gov.ph"></label>
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

    <!-- Approval dialog -->
    <dialog id="appr-dialog" aria-labelledby="appr-title">
        <form method="dialog" id="appr-form">
            <h2 id="appr-title">Approve account</h2>
            <p class="muted" id="appr-sub" style="margin-bottom:18px"></p>
            <label class="field" id="appr-zone" hidden><span>Assigned zone (collector)</span>
                <select>
                    <option>Zone 1 · Purok 1 to 3</option>
                    <option>Zone 2 · Purok 4 to 6</option>
                    <option>Zone 3 · Purok 7 to 9</option>
                </select></label>
            <fieldset id="appr-perms" hidden
                style="border:1px solid var(--line);border-radius:12px;padding:12px 16px;margin:0 0 16px">
                <legend class="muted" style="padding:0 8px">Staff permissions</legend>
                <div class="toolbar" style="margin:0"><label class="check"><input type="checkbox" checked> Waste
                        reports</label><label class="check"><input type="checkbox" checked> Collection
                        schedules</label><label class="check"><input type="checkbox" checked> Resident
                        concerns</label><label class="check"><input type="checkbox"> Redemptions</label><label
                        class="check"><input type="checkbox"> Community rewards (verify purok results)</label></div>
            </fieldset>
            <p class="notice" id="appr-note" style="margin-bottom:0"></p>
            <div class="btns"><button type="button" class="btn" id="appr-cancel">Cancel</button><button type="button"
                    class="btn btn-primary" id="appr-ok">Approve account</button></div>
        </form>
    </dialog>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../aos-init.js"></script>
</body>

</html>
