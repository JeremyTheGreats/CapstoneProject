<?php
require_once __DIR__ . '/env.php';

$getEnv = static function (string $key, string $default = ''): string {
    $val = $_ENV[$key] ?? getenv($key);
    return ($val !== false && $val !== null && $val !== '') ? (string) $val : $default;
};

define('GOOGLE_CLIENT_ID', $getEnv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', $getEnv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', $getEnv('GOOGLE_REDIRECT_URI', 'http://localhost/CapstoneProject/connection/google_callback.php'));

define('FACEBOOK_APP_ID', $getEnv('FACEBOOK_APP_ID'));
define('FACEBOOK_APP_SECRET', $getEnv('FACEBOOK_APP_SECRET'));
define('FACEBOOK_REDIRECT_URI', $getEnv('FACEBOOK_REDIRECT_URI', 'http://localhost/CapstoneProject/connection/facebook_callback.php'));

?>