<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/oauth.php';

$state = bin2hex(random_bytes(16));
$_SESSION['fb_oauth_state'] = $state;

$params = [
    'client_id'     => FACEBOOK_APP_ID,
    'redirect_uri'  => FACEBOOK_REDIRECT_URI,
    'state'         => $state,
    'scope'         => 'email,public_profile',
    'response_type' => 'code',
];

$authUrl = 'https://www.facebook.com/v21.0/dialog/oauth?' . http_build_query($params);
header('Location: ' . $authUrl);
exit();
?>