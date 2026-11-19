<?php
session_start();
require_once '../config/db.php';
require_once '../config/oauth.php';

// 1. Validate state & authorization code
if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
    die(
    'Debug -> GET state: ' . htmlspecialchars($_GET['state'] ?? 'NONE') .
    ' | SESSION state: ' . htmlspecialchars($_SESSION['oauth_state'] ?? 'NONE') .
    ' | Session ID: ' . session_id()
);
}
unset($_SESSION['oauth_state']);

// 2. Exchange authorization code for an access token
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'code' => $_GET['code'],
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'grant_type' => 'authorization_code',
    ]),
]);
$tokenResponse = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($tokenResponse['access_token'])) {
    die('Failed to authenticate with Google. <a href="../src/login.php">Back to login</a>');
}

// 3. Fetch user's Google profile info
$ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $tokenResponse['access_token']],
]);
$googleUser = json_decode(curl_exec($ch), true);
curl_close($ch);

$email = $googleUser['email'] ?? '';
$fname = $googleUser['given_name'] ?? 'Resident';
$lname = $googleUser['family_name'] ?? '';
$providerId = $googleUser['id'] ?? '';

if (empty($email)) {
    die('Google account did not return an email address.');
}

// 4. Check if user already exists in database
$stmt = mysqli_prepare($conn, "SELECT ID, fname, lname, role, status FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    // Link Google provider_id to existing account
    $upd = mysqli_prepare($conn, "UPDATE users SET provider = 'google', provider_id = ? WHERE ID = ?");
    mysqli_stmt_bind_param($upd, "si", $providerId, $user['ID']);
    mysqli_stmt_execute($upd);
    mysqli_stmt_close($upd);

    if (strtolower($user['status']) === 'active') {
        $_SESSION['User_fname'] = $user['fname'];
        $_SESSION['User_lname'] = $user['lname'];
        $_SESSION['User_role'] = $user['role'];
        $_SESSION['User_id'] = $user['ID'];

        $role = strtolower($user['role']);
        if ($role === 'admin')
            header("Location: ../src/admin-dashboard.php");
        elseif ($role === 'official')
            header("Location: ../src/official-dashboard.php");
        elseif ($role === 'collector')
            header("Location: ../src/collector-dashboard.php");
        else
            header("Location: ../src/resident-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Your account is still pending Admin approval.'); window.location.href='../src/login.php';</script>";
        exit();
    }
} else {
    // 5. New Google user: register as Resident with 'pending' status
    $role = 'Resident';
    $status = 'pending';
    $provider = 'google';

    $ins = mysqli_prepare($conn, "INSERT INTO users (fname, lname, email, role, status, provider, provider_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($ins, "sssssss", $fname, $lname, $email, $role, $status, $provider, $providerId);
    mysqli_stmt_execute($ins);
    mysqli_stmt_close($ins);

    echo "<script>alert('Google account registered! Please wait for Admin approval before logging in.'); window.location.href='../src/login.php';</script>";
    exit();
}
?>