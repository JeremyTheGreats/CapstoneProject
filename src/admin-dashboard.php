<?php
session_start();
if(empty($_SESSION['User_id'])||strtolower((string)($_SESSION['User_role']??''))!=='admin'){header('Location: login.php');exit;}
require_once __DIR__.'/../config/dashboard.php';$uid=(int)$_SESSION['User_id'];
$stmt=$conn->prepare('SELECT fname,lname,email,phone,address FROM users WHERE ID=?');$stmt->bind_param('i',$uid);$stmt->execute();$user=$stmt->get_result()->fetch_assoc()?:[];
$users=$conn->query('SELECT ID,fname,lname,email,address,role,status FROM users ORDER BY ID DESC')->fetch_all(MYSQLI_ASSOC);
$reports=$conn->query("SELECT wr.*,u.fname,u.lname FROM waste_reports wr JOIN users u ON u.ID=wr.user_id ORDER BY FIELD(wr.status,'pending','verified','resolved','rejected'),wr.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$observations=$conn->query("SELECT o.*,c.fname collector_fname,c.lname collector_lname,r.fname resident_fname,r.lname resident_lname FROM segregation_observations o JOIN users c ON c.ID=o.collector_id JOIN users r ON r.ID=o.resident_id ORDER BY FIELD(o.status,'pending','approved','rejected'),o.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$claims=$conn->query("SELECT rc.claim_id,rc.points_spent,rc.status,rc.claimed_at,u.fname,u.lname,r.reward_name FROM reward_claims rc JOIN users u ON u.ID=rc.user_id JOIN rewards r ON r.reward_id=rc.reward_id ORDER BY FIELD(rc.status,'pending','claimed','cancelled'),rc.claimed_at DESC")->fetch_all(MYSQLI_ASSOC);
$schedules=$conn->query("SELECT cs.*,u.fname,u.lname FROM collection_schedules cs LEFT JOIN users u ON u.ID=cs.collector_id ORDER BY cs.schedule_date DESC,cs.start_time DESC LIMIT 100")->fetch_all(MYSQLI_ASSOC);
$collectors=$conn->query("SELECT ID,fname,lname FROM users WHERE LOWER(role)='collector' AND LOWER(status)='active' ORDER BY fname,lname")->fetch_all(MYSQLI_ASSOC);
$rewards=$conn->query('SELECT reward_id,reward_name,description,points_required,stock_qty,is_active FROM rewards ORDER BY reward_name')->fetch_all(MYSQLI_ASSOC);
$pending=(int)$conn->query("SELECT COUNT(*) c FROM users WHERE LOWER(status)='pending'")->fetch_assoc()['c'];$notice=dashboard_notice();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Administrator dashboard – BarangayEcoCare</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/dashboard.css">
    <link rel="stylesheet" href="../style/admin-dashboard.css">
</head>

<body>
    <div class="shell">
        <aside class="sidebar">
            <div><a href="../index.php" class="logo">Barangay<b>EcoCare</b></a>
                <p class="ws">Administrator · full access</p>
            </div>
            <nav class="nav"><a href="#v1">Overview</a><a href="#v2">Accounts <span
                        class="count"><?=$pending?></span></a><a href="#v3">Reports</a><a href="#v4">Schedules</a><a
                    href="#v5">Rewards</a><a href="#v6">Redemptions</a><a href="#v7">Profile</a><a href="#v8">Segregation</a></nav>
            <div class="me">
                <div class="me-info"><span class="avatar"><?=e(strtoupper(substr($user['fname']??'A',0,1)))?></span>
                    <div><?=e(($user['fname']??'').' '.($user['lname']??''))?><small>Administrator</small></div>
                </div><a href="logout.php" class="btn">Log out</a>
            </div>
        </aside>
        <main class="main">
            <div class="views"><?php if($notice):?><p class="notice" role="status"><?=e($notice)?></p><?php endif;?>
                <section class="view" id="v1">
                    <h1>Admin overview</h1>
                    <p class="sub">Current activity from the database.</p>
                    <div class="grid g4">
                        <article class="card stat"><small>Registered users</small><b><?=count($users)?></b></article>
                        <article class="card stat"><small>Open
                                reports</small><b><?=count(array_filter($reports,fn($r)=>in_array($r['status'],['pending','verified'],true)))?></b>
                        </article>
                        <article class="card stat"><small>Pending approvals</small><b><?=$pending?></b></article>
                        <article class="card stat"><small>Reward
                                claims</small><b><?=count(array_filter($claims,fn($c)=>$c['status']==='pending'))?></b>
                        </article>
                    </div>
                </section>
                <section class="view" id="v2">
                    <h1>Account approvals</h1>
                    <p class="sub">Review resident, collector, and official accounts.</p>
                    <article class="card table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name / email</th>
                                    <th>Role</th>
                                    <th>Area</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody><?php foreach($users as $row):$status=strtolower($row['status']);?><tr>
                                    <td><strong><?=e($row['fname'].' '.$row['lname'])?></strong><br><small><?=e($row['email'])?></small>
                                    </td>
                                    <td><?=e($row['role'])?></td>
                                    <td><?=e($row['address']?:'—')?></td>
                                    <td><?=e(ucfirst($status))?></td>
                                    <td><?php if(strtolower($row['role'])==='admin'):?><span class="muted">Protected</span><?php elseif(!in_array(strtolower($row['role']),['resident','official','collector'],true)):?><form action="approve_user.php" method="post" class="acts"><?php dashboard_form_token();?><input type="hidden" name="id" value="<?=(int)$row['ID']?>"><input type="hidden" name="action" value="set_role"><select name="role" aria-label="Assign role"><option value="Resident">Resident</option><option value="Collector">Collector</option><option value="Official">Official</option></select><button class="btn btn-primary btn-sm">Set role</button></form><?php else:?><form action="approve_user.php" method="post" class="acts"><?php dashboard_form_token();?><input type="hidden" name="id" value="<?=(int)$row['ID']?>"><button class="btn btn-primary btn-sm" name="action" value="<?=$status==='pending'?'approve':'toggle'?>"><?=$status==='pending'?'Approve':'Toggle status'?></button><?php if($status==='pending'):?><button class="btn btn-sm" name="action" value="reject">Reject</button><?php endif;?></form><?php endif;?></td>
                                </tr><?php endforeach;?></tbody>
                        </table>
                    </article>
                </section>
                <section class="view" id="v3">
                    <h1>Waste reports</h1>
                    <article class="card">
                        <ul class="rows"><?php if(!$reports):?><li><span class="muted">No reports submitted.</span></li>
                            <?php else:foreach($reports as $r):?><li>
                                <div><strong><?=e($r['category'])?> · <?=e($r['location_details'])?></strong><small>By
                                        <?=e($r['fname'].' '.$r['lname'])?> ·
                                        <?=e(date('M j, Y',strtotime($r['created_at'])))?></small><small><?=nl2br(e($r['description']))?></small><?php if($r['photo_path']):?><small><a
                                            href="../<?=e($r['photo_path'])?>" target="_blank" rel="noopener">View
                                            photo</a></small><?php endif;?></div>
                                <form action="dashboard-action.php" method="post" class="acts">
                                    <?php dashboard_form_token();?><input type="hidden" name="action"
                                        value="report_status"><input type="hidden" name="section" value="v3"><input
                                        type="hidden" name="report_id" value="<?=(int)$r['report_id']?>"><select
                                        name="status">
                                        <option value="verified" <?=$r['status']==='verified'?'selected':''?>>Verified
                                        </option>
                                        <option value="resolved" <?=$r['status']==='resolved'?'selected':''?>>Resolved
                                        </option>
                                        <option value="rejected" <?=$r['status']==='rejected'?'selected':''?>>Rejected
                                        </option>
                                    </select><input type="number" name="points" min="0" max="1000" value="0"
                                        title="Award points"><button class="btn btn-primary btn-sm">Save</button></form>
                            </li><?php endforeach;endif;?></ul>
                    </article>
                </section>
                <section class="view" id="v4">
                    <h1>Collection schedules</h1>
                    <form class="card grid g3" action="dashboard-action.php" method="post">
                        <?php dashboard_form_token();?><input type="hidden" name="action" value="create_schedule"><input
                            type="hidden" name="section" value="v4"><label class="field"><span>Area / purok</span><input
                                name="area_purok" required></label><label class="field"><span>Date</span><input
                                type="date" name="schedule_date" min="<?=date('Y-m-d')?>" required></label><label
                            class="field"><span>Waste type</span><input name="waste_type" required></label><label
                            class="field"><span>Start time</span><input type="time" name="start_time"></label><label
                            class="field"><span>End time</span><input type="time" name="end_time"></label><label
                            class="field"><span>Collector</span><select name="collector_id">
                                <option value="">Unassigned</option><?php foreach($collectors as $c):?><option
                                    value="<?=(int)$c['ID']?>"><?=e($c['fname'].' '.$c['lname'])?></option>
                                <?php endforeach;?>
                            </select></label><button class="btn btn-primary">Create schedule</button></form>
                    <article class="card" style="margin-top:var(--gap)">
                        <ul class="rows"><?php if(!$schedules):?><li><span class="muted">No schedules posted.</span>
                            </li><?php else:foreach($schedules as $s):?><li>
                                <div><strong><?=e($s['area_purok'])?> ·
                                        <?=e($s['waste_type'])?></strong><small><?=e(date('D, M j, Y',strtotime($s['schedule_date'])))?>
                                        · <?=e($s['fname']?($s['fname'].' '.$s['lname']):'Unassigned')?></small></div>
                                <span class="badge b-pending"><?=e(ucwords(str_replace('_',' ',$s['status'])))?></span>
                            </li><?php endforeach;endif;?></ul>
                    </article>
                </section>
                <section class="view" id="v5">
                    <h1>Reward catalog</h1>
                    <form class="card grid g4" action="dashboard-action.php" method="post">
                        <?php dashboard_form_token();?><input type="hidden" name="action" value="create_reward"><input
                            type="hidden" name="section" value="v5"><label class="field"><span>Reward name</span><input
                                name="reward_name" required></label><label class="field"><span>Description</span><input
                                name="description"></label><label class="field"><span>Points required</span><input
                                type="number" name="points_required" min="1" required></label><label
                            class="field"><span>Stock</span><input type="number" name="stock_qty" min="0" value="0"
                                required></label><button class="btn btn-primary">Add reward</button></form>
                    <article class="card" style="margin-top:var(--gap)">
                        <ul class="rows"><?php if(!$rewards):?><li><span class="muted">No rewards in the catalog.</span>
                            </li><?php else:foreach($rewards as $r):?><li>
                                <div><strong><?=e($r['reward_name'])?></strong><small><?=number_format((int)$r['points_required'])?>
                                        points · <?=number_format((int)$r['stock_qty'])?> in stock ·
                                        <?=e($r['description'])?></small></div>
                                <form action="dashboard-action.php" method="post"><?php dashboard_form_token();?><input
                                        type="hidden" name="action" value="toggle_reward"><input type="hidden"
                                        name="section" value="v5"><input type="hidden" name="reward_id"
                                        value="<?=(int)$r['reward_id']?>"><button
                                        class="btn btn-sm"><?=$r['is_active']?'Deactivate':'Activate'?></button></form>
                            </li><?php endforeach;endif;?></ul>
                    </article>
                </section>
                <section class="view" id="v6">
                    <h1>Reward redemptions</h1>
                    <article class="card">
                        <ul class="rows"><?php if(!$claims):?><li><span class="muted">No claims.</span></li>
                            <?php else:foreach($claims as $c):?><li>
                                <div><strong><?=e($c['reward_name'])?> ·
                                        <?=e($c['fname'].' '.$c['lname'])?></strong><small><?=number_format((int)$c['points_spent'])?>
                                        points · <?=e(date('M j, Y',strtotime($c['claimed_at'])))?></small></div>
                                <?php if($c['status']==='pending'):?><form action="dashboard-action.php" method="post"
                                    class="acts"><?php dashboard_form_token();?><input type="hidden" name="action"
                                        value="claim_status"><input type="hidden" name="section" value="v6"><input
                                        type="hidden" name="claim_id" value="<?=(int)$c['claim_id']?>"><button
                                        class="btn btn-primary btn-sm" name="status"
                                        value="claimed">Approve</button><button class="btn btn-sm" name="status"
                                        value="cancelled">Cancel and refund</button></form><?php else:?><span
                                    class="badge b-pending"><?=e(ucfirst($c['status']))?></span><?php endif;?>
                            </li><?php endforeach;endif;?></ul>
                    </article>
                </section>
                <section class="view" id="v7">
                    <h1>My profile</h1>
                    <div class="grid g2">
                        <form class="card" action="dashboard-action.php" method="post">
                            <?php dashboard_form_token();?><input type="hidden" name="action"
                                value="update_profile"><input type="hidden" name="section"
                                value="v7"><?php foreach(['fname'=>'First name','lname'=>'Last name','email'=>'Email','phone'=>'Phone','address'=>'Address'] as $key=>$label):?><label
                                class="field"><span><?=e($label)?></span><input name="<?=e($key)?>"
                                    value="<?=e($user[$key]??'')?>" <?=$key==='email'?'type="email" required':''?>
                                    <?=$key==='fname'||$key==='lname'?'required':''?>></label><?php endforeach;?><button
                                class="btn btn-primary">Save profile</button></form>
                        <form class="card" action="dashboard-action.php" method="post">
                            <?php dashboard_form_token();?><input type="hidden" name="action"
                                value="change_password"><input type="hidden" name="section" value="v7"><label
                                class="field"><span>New password</span><input type="password" name="password"
                                    minlength="8" required></label><label class="field"><span>Confirm
                                    password</span><input type="password" name="confirm" minlength="8"
                                    required></label><button class="btn btn-primary">Change password</button></form>
                    </div>
                </section>
                <section class="view" id="v8"><h1>Segregation observations</h1><p class="sub">Review collector evidence and award points to the resident.</p><article class="card"><ul class="rows"><?php if(!$observations):?><li><span class="muted">No observations submitted.</span></li><?php else:foreach($observations as $o):?><li><div><strong><?=e($o['area_purok'])?> · <?=e($o['location_details'])?></strong><small><?=e($o['proper_bins'])?> of <?=e($o['total_bins'])?> bins sorted · Resident <?=e($o['resident_fname'].' '.$o['resident_lname'])?> · Collector <?=e($o['collector_fname'].' '.$o['collector_lname'])?></small><small><?=nl2br(e($o['notes']))?></small><?php if($o['photo_path']):?><small><a href="../<?=e($o['photo_path'])?>" target="_blank" rel="noopener">View photo evidence</a></small><?php endif;?></div><?php if($o['status']==='pending'):?><form action="dashboard-action.php" method="post" class="acts"><?php dashboard_form_token();?><input type="hidden" name="action" value="observation_status"><input type="hidden" name="section" value="v8"><input type="hidden" name="observation_id" value="<?=(int)$o['observation_id']?>"><input type="number" name="points" min="0" max="1000" value="50" aria-label="Award points"><button class="btn btn-primary btn-sm" name="status" value="approved">Approve</button><button class="btn btn-sm" name="status" value="rejected">Reject</button></form><?php else:?><span class="badge b-pending"><?=e(ucfirst($o['status']))?></span><?php endif;?></li><?php endforeach;endif;?></ul></article></section>
            </div>
        </main>
    </div>
</body>

</html>
