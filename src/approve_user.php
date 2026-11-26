<?php
session_start();
if (empty($_SESSION['User_id']) || strtolower((string)($_SESSION['User_role'] ?? '')) !== 'admin') {
    header('Location: login.php'); exit;
}
require_once __DIR__ . '/../config/dashboard.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals((string)($_SESSION['csrf_token'] ?? ''), (string)($_POST['csrf_token'] ?? ''))) {
    http_response_code(400); exit('Invalid form submission. Reload the page and try again.');
}
$id = (int)($_POST['id'] ?? 0);
$action = strtolower((string)($_POST['action'] ?? ''));
if ($id < 1 || !in_array($action, ['approve','reject','toggle','set_role'], true)) {
    $_SESSION['dashboard_notice'] = 'Invalid account action.';
    header('Location: admin-dashboard.php#v2'); exit;
}
$stmt = $conn->prepare('SELECT role,status FROM users WHERE ID=?');
$stmt->bind_param('i', $id); $stmt->execute(); $target = $stmt->get_result()->fetch_assoc();
if (!$target || strtolower($target['role']) === 'admin') {
    $_SESSION['dashboard_notice'] = $target ? 'Administrator accounts cannot be changed here.' : 'Account not found.';
    header('Location: admin-dashboard.php#v2'); exit;
}
if ($action === 'set_role') {
    $role = (string)($_POST['role'] ?? '');
    $roleMap = ['resident'=>'Resident', 'collector'=>'Collector', 'official'=>'Official'];
    if (!isset($roleMap[strtolower($role)])) {
        $_SESSION['dashboard_notice'] = 'Choose a valid non-admin role.';
        header('Location: admin-dashboard.php#v2'); exit;
    }
    $role = $roleMap[strtolower($role)];
    $update = $conn->prepare("UPDATE users SET role=?, status='pending' WHERE ID=?");
    $update->bind_param('si', $role, $id); $update->execute();
    $_SESSION['dashboard_notice'] = 'Account role updated.';
    header('Location: admin-dashboard.php#v2'); exit;
}
$status = match ($action) {
    'approve' => 'active',
    'reject' => 'inactive',
    'toggle' => strtolower($target['status']) === 'active' ? 'inactive' : 'active',
};
$update = $conn->prepare('UPDATE users SET status=? WHERE ID=?');
$update->bind_param('si', $status, $id); $update->execute();
$_SESSION['dashboard_notice'] = 'Account status updated.';
header('Location: admin-dashboard.php#v2'); exit;
