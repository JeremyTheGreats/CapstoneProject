<?php
session_start();
require_once "../config/db.php";


if (!isset($_SESSION['User_id']) || strtolower($_SESSION['User_role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $userId = intval($_GET['id']);
    $action = strtolower(trim($_GET['action']));

    if ($userId <= 0) {
        header("Location: admin-dashboard.php?error=invalid_id#v2");
        exit();
    }

    // 2. Fetch target user role and current status
    $stmt = mysqli_prepare($conn, "SELECT role, status FROM users WHERE ID = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        header("Location: admin-dashboard.php?error=user_not_found#v2");
        exit();
    }

    // 3. Prevent modifying Admin accounts
    if (strtolower($user['role']) === 'admin') {
        header("Location: admin-dashboard.php?error=cannot_modify_admin#v2");
        exit();
    }

    // 4. Determine new status
    if ($action === 'approve') {
        $newStatus = 'active';
    } elseif ($action === 'reject') {
        $newStatus = 'inactive';
    } elseif ($action === 'toggle') {
        $newStatus = (strtolower($user['status']) === 'active') ? 'Inactive' : 'Active';
    } else {
        header("Location: admin-dashboard.php?error=invalid_action#v2");
        exit();
    }

    // 5. Update database
    $upd = mysqli_prepare($conn, "UPDATE users SET status = ? WHERE ID = ?");
    mysqli_stmt_bind_param($upd, "si", $newStatus, $userId);

    if (mysqli_stmt_execute($upd)) {
        mysqli_stmt_close($upd);
        header("Location: admin-dashboard.php?success=status_updated#v2");
        exit();
    } else {
        mysqli_stmt_close($upd);
        header("Location: admin-dashboard.php?error=update_failed#v2");
        exit();
    }
} else {
    header("Location: admin-dashboard.php?error=missing_params#v2");
    exit();
}
?>