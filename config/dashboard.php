<?php
require_once __DIR__ . '/db.php';

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function dashboard_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function dashboard_form_token(): void
{
    echo '<input type="hidden" name="csrf_token" value="' . e(dashboard_csrf_token()) . '">';
}

function dashboard_notice(): string
{
    $message = trim((string)($_SESSION['dashboard_notice'] ?? ''));
    unset($_SESSION['dashboard_notice']);
    return $message;
}
