<?php
session_start();
require_once '../config/db.php';
require_once '../config/oauth.php';

// 1. Validate state & authorization code
if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['fb_oauth_state'] ?? '')) {
    die('Invalid Facebook OAuth state or login cancelled. <a href="../src/login.php">Back to login</a>');
}
unset($_SESSION['fb_oauth_state']);

// 2. Exchange authorization code for an access token
$tokenUrl = 'https://graph.facebook.com/v21.0/oauth/access_token?' . http_build_query([
    'client_id' => FACEBOOK_APP_ID,
    'client_secret' => FACEBOOK_APP_SECRET,
    'redirect_uri' => FACEBOOK_REDIRECT_URI,
    'code' => $_GET['code'],
]);

$ch = curl_init($tokenUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
]);
$tokenResponse = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($tokenResponse['access_token'])) {
    die('Failed to authenticate with Facebook. <a href="../src/login.php">Back to login</a>');
}

// 3. Fetch profile fields from Graph API
$profileUrl = 'https://graph.facebook.com/v21.0/me?' . http_build_query([
    'fields' => 'id,first_name,last_name,email',
    'access_token' => $tokenResponse['access_token'],
]);

$ch = curl_init($profileUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
]);
$fbUser = json_decode(curl_exec($ch), true);
curl_close($ch);

$providerId = $fbUser['id'] ?? '';
$fname = $fbUser['first_name'] ?? 'Resident';
$lname = $fbUser['last_name'] ?? '';
$email = $fbUser['email'] ?? '';

if (empty($providerId)) {
    die('Could not retrieve Facebook profile information. <a href="../src/login.php">Back to login</a>');
}

// 4. Match existing user by email (if provided) or by provider_id
if (!empty($email)) {
    $stmt = mysqli_prepare($conn, "SELECT ID, fname, lname, role, status FROM users WHERE email = ? OR (provider = 'facebook' AND provider_id = ?) LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ss", $email, $providerId);
} else {
    $stmt = mysqli_prepare($conn, "SELECT ID, fname, lname, role, status FROM users WHERE provider = 'facebook' AND provider_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $providerId);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    // Link Facebook provider info to existing account
    $upd = mysqli_prepare($conn, "UPDATE users SET provider = 'facebook', provider_id = ? WHERE ID = ?");
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
    $safeEmail = !empty($email) ? $email : ('fb_' . $providerId . '@noemail.local');
    $role = 'Resident';
    $status = 'pending';
    $provider = 'facebook';

    $ins = mysqli_prepare($conn, "INSERT INTO users (fname, lname, email, role, status, provider, provider_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($ins, "sssssss", $fname, $lname, $safeEmail, $role, $status, $provider, $providerId);
    mysqli_stmt_execute($ins);
    mysqli_stmt_close($ins);

    echo "<script>alert('Facebook account registered! Please wait for Admin approval before logging in.'); window.location.href='../src/login.php';</script>";
    exit();
}
?>