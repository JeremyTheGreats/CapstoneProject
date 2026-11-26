<?php
session_start();
require_once __DIR__ . '/../config/dashboard.php';

if (empty($_SESSION['User_id'])) {
    header('Location: login.php');
    exit;
}
$role = strtolower((string)($_SESSION['User_role'] ?? ''));
$uid = (int)$_SESSION['User_id'];
$target = [
    'resident' => 'resident-dashboard.php', 'official' => 'official-dashboard.php',
    'admin' => 'admin-dashboard.php', 'collector' => 'collector-dashboard.php'
][$role] ?? 'login.php';
$section = preg_replace('/[^a-z0-9_-]/i', '', (string)($_POST['section'] ?? 'v1'));
$redirect = static function (string $message) use ($target, $section): never {
    $_SESSION['dashboard_notice'] = $message;
    header('Location: ' . $target . '#' . $section);
    exit;
};
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals((string)($_SESSION['csrf_token'] ?? ''), (string)($_POST['csrf_token'] ?? ''))) {
    http_response_code(400);
    exit('Invalid form submission. Reload the page and try again.');
}
$action = (string)($_POST['action'] ?? '');

try {
    if ($action === 'submit_report' && $role === 'resident') {
        $category = trim((string)($_POST['category'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $location = trim((string)($_POST['location'] ?? ''));
        $allowed = ['Illegal dumping', 'Uncollected garbage', 'Overflowing bin', 'Clogged canal', 'Other'];
        if (!in_array($category, $allowed, true) || $description === '' || $location === '') {
            $redirect('Choose a report type and provide the details and location.');
        }
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK || $_FILES['photo']['size'] > 5 * 1024 * 1024) {
                $redirect('Photo upload failed. Choose an image smaller than 5 MB.');
            }
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['photo']['tmp_name']);
            $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (!isset($extensions[$mime])) $redirect('Use a JPG, PNG, or WEBP image.');
            $directory = __DIR__ . '/../uploads/reports';
            if (!is_dir($directory) && !mkdir($directory, 0755, true)) $redirect('Could not store the uploaded photo.');
            $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $directory . '/' . $filename)) $redirect('Could not store the uploaded photo.');
            $photoPath = 'uploads/reports/' . $filename;
        }
        $stmt = $conn->prepare('INSERT INTO waste_reports (user_id, category, description, photo_path, location_details) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('issss', $uid, $category, $description, $photoPath, $location);
        $stmt->execute();
        $redirect('Your report was submitted.');
    }

    if ($action === 'submit_observation' && $role === 'collector') {
        $residentId = (int)($_POST['resident_id'] ?? 0);
        $area = trim((string)($_POST['area_purok'] ?? ''));
        $location = trim((string)($_POST['location_details'] ?? ''));
        $proper = (int)($_POST['proper_bins'] ?? -1); $total = (int)($_POST['total_bins'] ?? 0);
        $notes = trim((string)($_POST['notes'] ?? ''));
        if ($area === '' || $location === '' || $total < 1 || $proper < 0 || $proper > $total) $redirect('Enter the area, location, and valid bin counts.');
        $residentCheck = $conn->prepare("SELECT ID FROM users WHERE ID=? AND LOWER(role)='resident' AND LOWER(status)='active'");
        $residentCheck->bind_param('i', $residentId); $residentCheck->execute();
        if (!$residentCheck->get_result()->fetch_assoc()) $redirect('Choose an active resident account.');
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK || $_FILES['photo']['size'] > 5 * 1024 * 1024) $redirect('Photo upload failed. Choose an image smaller than 5 MB.');
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['photo']['tmp_name']);
            $extensions = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
            if (!isset($extensions[$mime])) $redirect('Use a JPG, PNG, or WEBP image.');
            $directory = __DIR__ . '/../uploads/reports';
            if (!is_dir($directory) && !mkdir($directory, 0755, true)) $redirect('Could not store the uploaded photo.');
            $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $directory . '/' . $filename)) $redirect('Could not store the uploaded photo.');
            $photoPath = 'uploads/reports/' . $filename;
        }
        $stmt = $conn->prepare('INSERT INTO segregation_observations (collector_id,resident_id,area_purok,location_details,proper_bins,total_bins,notes,photo_path) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->bind_param('iissiiss', $uid, $residentId, $area, $location, $proper, $total, $notes, $photoPath);
        $stmt->execute();
        $redirect('Segregation observation sent for review.');
    }

    if ($action === 'observation_status' && in_array($role, ['official','admin'], true)) {
        $observationId = (int)($_POST['observation_id'] ?? 0);
        $status = (string)($_POST['status'] ?? '');
        $points = max(0, min(1000, (int)($_POST['points'] ?? 0)));
        if (!in_array($status, ['approved','rejected'], true)) $redirect('Invalid observation status.');
        $conn->begin_transaction();
        $stmt = $conn->prepare('SELECT resident_id,area_purok,proper_bins,total_bins,status FROM segregation_observations WHERE observation_id=? FOR UPDATE');
        $stmt->bind_param('i',$observationId);$stmt->execute();$observation=$stmt->get_result()->fetch_assoc();
        if (!$observation || $observation['status'] !== 'pending') throw new RuntimeException('This observation has already been reviewed.');
        $upd=$conn->prepare('UPDATE segregation_observations SET status=? WHERE observation_id=?');$upd->bind_param('si',$status,$observationId);$upd->execute();
        if ($status==='approved' && $points>0) {
            $remarks='Verified segregation observation, '.$observation['area_purok'].' ('.$observation['proper_bins'].'/'.$observation['total_bins'].' bins)';
            $tx=$conn->prepare('INSERT INTO point_transactions (user_id,points_delta,remarks) VALUES (?,?,?)');
            $tx->bind_param('iis',$observation['resident_id'],$points,$remarks);$tx->execute();
        }
        $conn->commit();
        $redirect('Segregation observation reviewed.');
    }

    if ($action === 'claim_reward' && $role === 'resident') {
        $rewardId = (int)($_POST['reward_id'] ?? 0);
        $conn->begin_transaction();
        $userLock = $conn->prepare('SELECT ID FROM users WHERE ID=? FOR UPDATE');
        $userLock->bind_param('i', $uid); $userLock->execute();
        $rewardStmt = $conn->prepare('SELECT reward_name, points_required, stock_qty FROM rewards WHERE reward_id=? AND is_active=1 FOR UPDATE');
        $rewardStmt->bind_param('i', $rewardId); $rewardStmt->execute();
        $reward = $rewardStmt->get_result()->fetch_assoc();
        if (!$reward || (int)$reward['stock_qty'] < 1) throw new RuntimeException('This reward is unavailable.');
        $balStmt = $conn->prepare('SELECT COALESCE(SUM(points_delta),0) AS balance FROM point_transactions WHERE user_id=?');
        $balStmt->bind_param('i', $uid); $balStmt->execute();
        $balance = (int)$balStmt->get_result()->fetch_assoc()['balance'];
        $cost = (int)$reward['points_required'];
        if ($balance < $cost) throw new RuntimeException('You do not have enough points for this reward.');
        $tx = $conn->prepare('INSERT INTO point_transactions (user_id, points_delta, remarks) VALUES (?, ?, ?)');
        $delta = -$cost; $remarks = 'Reward claim: ' . $reward['reward_name'];
        $tx->bind_param('iis', $uid, $delta, $remarks); $tx->execute();
        $claim = $conn->prepare("INSERT INTO reward_claims (user_id, reward_id, points_spent, status) VALUES (?, ?, ?, 'pending')");
        $claim->bind_param('iii', $uid, $rewardId, $cost); $claim->execute();
        $stock = $conn->prepare('UPDATE rewards SET stock_qty=stock_qty-1 WHERE reward_id=? AND stock_qty>0');
        $stock->bind_param('i', $rewardId); $stock->execute();
        if ($stock->affected_rows !== 1) throw new RuntimeException('This reward is out of stock.');
        $conn->commit();
        $redirect('Reward requested. Points are reserved while staff reviews your claim.');
    }

    if ($action === 'report_status' && in_array($role, ['official', 'admin'], true)) {
        $reportId = (int)($_POST['report_id'] ?? 0);
        $status = (string)($_POST['status'] ?? '');
        if (!in_array($status, ['verified', 'resolved', 'rejected'], true)) $redirect('Invalid report status.');
        $conn->begin_transaction();
        $stmt = $conn->prepare('SELECT user_id, status FROM waste_reports WHERE report_id=? FOR UPDATE');
        $stmt->bind_param('i', $reportId); $stmt->execute(); $report = $stmt->get_result()->fetch_assoc();
        if (!$report) throw new RuntimeException('Report not found.');
        $resolvedAt = $status === 'resolved' ? date('Y-m-d H:i:s') : null;
        $upd = $conn->prepare('UPDATE waste_reports SET status=?, resolved_at=? WHERE report_id=?');
        $upd->bind_param('ssi', $status, $resolvedAt, $reportId); $upd->execute();
        $conn->commit();
        $redirect('Report updated.');
    }

    if ($action === 'claim_status' && in_array($role, ['official', 'admin'], true)) {
        $claimId = (int)($_POST['claim_id'] ?? 0);
        $status = (string)($_POST['status'] ?? '');
        if (!in_array($status, ['claimed', 'cancelled'], true)) $redirect('Invalid redemption status.');
        $conn->begin_transaction();
        $stmt = $conn->prepare('SELECT user_id, reward_id, points_spent, status FROM reward_claims WHERE claim_id=? FOR UPDATE');
        $stmt->bind_param('i', $claimId); $stmt->execute(); $claim = $stmt->get_result()->fetch_assoc();
        if (!$claim || $claim['status'] !== 'pending') throw new RuntimeException('This redemption has already been processed.');
        $upd = $conn->prepare('UPDATE reward_claims SET status=? WHERE claim_id=?');
        $upd->bind_param('si', $status, $claimId); $upd->execute();
        if ($status === 'cancelled') {
            $refund = $conn->prepare('INSERT INTO point_transactions (user_id, points_delta, remarks) VALUES (?, ?, ?)');
            $points = (int)$claim['points_spent']; $remarks = 'Refund for cancelled reward claim';
            $refund->bind_param('iis', $claim['user_id'], $points, $remarks); $refund->execute();
            $stock = $conn->prepare('UPDATE rewards SET stock_qty=stock_qty+1 WHERE reward_id=?');
            $stock->bind_param('i', $claim['reward_id']); $stock->execute();
        }
        $conn->commit();
        $redirect('Redemption updated.');
    }

    if ($action === 'create_schedule' && in_array($role, ['official', 'admin'], true)) {
        $area = trim((string)($_POST['area_purok'] ?? ''));
        $date = (string)($_POST['schedule_date'] ?? '');
        $waste = trim((string)($_POST['waste_type'] ?? ''));
        $collector = (int)($_POST['collector_id'] ?? 0) ?: null;
        $start = (string)($_POST['start_time'] ?? '') ?: null;
        $end = (string)($_POST['end_time'] ?? '') ?: null;
        if ($area === '' || $waste === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $redirect('Enter an area, date, and waste type.');
        if ($collector !== null) {
            $checkCollector = $conn->prepare("SELECT ID FROM users WHERE ID=? AND LOWER(role)='collector' AND LOWER(status)='active'");
            $checkCollector->bind_param('i', $collector); $checkCollector->execute();
            if (!$checkCollector->get_result()->fetch_assoc()) $redirect('Choose an active collector account.');
        }
        $stmt = $conn->prepare("INSERT INTO collection_schedules (collector_id, area_purok, schedule_date, start_time, end_time, waste_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $collector, $area, $date, $start, $end, $waste); $stmt->execute();
        $redirect('Collection schedule created.');
    }

    if ($action === 'create_reward' && $role === 'admin') {
        $name = trim((string)($_POST['reward_name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $cost = (int)($_POST['points_required'] ?? 0);
        $stock = (int)($_POST['stock_qty'] ?? 0);
        if ($name === '' || $cost < 1 || $stock < 0) $redirect('Enter a reward name, positive point cost, and nonnegative stock.');
        $stmt = $conn->prepare('INSERT INTO rewards (reward_name, description, points_required, stock_qty) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $name, $description, $cost, $stock); $stmt->execute();
        $redirect('Reward added to the catalog.');
    }

    if ($action === 'toggle_reward' && $role === 'admin') {
        $rewardId = (int)($_POST['reward_id'] ?? 0);
        $stmt = $conn->prepare('UPDATE rewards SET is_active=IF(is_active=1,0,1) WHERE reward_id=?');
        $stmt->bind_param('i', $rewardId); $stmt->execute();
        $redirect($stmt->affected_rows ? 'Reward availability updated.' : 'Reward was not found.');
    }

    if ($action === 'complete_schedule' && $role === 'collector') {
        $scheduleId = (int)($_POST['schedule_id'] ?? 0);
        $stmt = $conn->prepare("UPDATE collection_schedules SET status='completed' WHERE schedule_id=? AND collector_id=? AND status IN ('scheduled','in_progress')");
        $stmt->bind_param('ii', $scheduleId, $uid); $stmt->execute();
        $redirect($stmt->affected_rows ? 'Collection marked complete.' : 'Schedule not found or not assigned to you.');
    }

    if ($action === 'update_profile') {
        $fname = trim((string)($_POST['fname'] ?? '')); $lname = trim((string)($_POST['lname'] ?? ''));
        $email = trim((string)($_POST['email'] ?? '')); $phone = trim((string)($_POST['phone'] ?? ''));
        $address = trim((string)($_POST['address'] ?? ''));
        if ($fname === '' || $lname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $redirect('Enter a valid name and email address.');
        $stmt = $conn->prepare('UPDATE users SET fname=?, lname=?, email=?, phone=?, address=? WHERE ID=?');
        $stmt->bind_param('sssssi', $fname, $lname, $email, $phone, $address, $uid); $stmt->execute();
        $_SESSION['User_fname'] = $fname; $_SESSION['User_lname'] = $lname;
        $redirect('Profile saved.');
    }

    if ($action === 'change_password') {
        $password = (string)($_POST['password'] ?? ''); $confirm = (string)($_POST['confirm'] ?? '');
        if (strlen($password) < 8 || $password !== $confirm) $redirect('Passwords must match and contain at least 8 characters.');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE users SET password=? WHERE ID=?');
        $stmt->bind_param('si', $hash, $uid); $stmt->execute();
        $redirect('Password updated.');
    }

    $redirect('This action is not available for your account.');
} catch (Throwable $exception) {
    if ($conn->errno || $conn->ping()) $conn->rollback();
    error_log('EcoCare dashboard action failed: ' . $exception->getMessage());
    $redirect($exception instanceof RuntimeException ? $exception->getMessage() : 'The action could not be completed. Please try again.');
}
