<?php
/*
 * Global CSRF protection
 *  - rejects any POST request that does not carry the session's token
 *  - automatically adds the hidden token field to every <form method="post"> in the page output
 * Included once from the project's bootstrap/config file.
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }

if (!function_exists('csrf_token')) {
    function csrf_token() { return $_SESSION['csrf_token']; }
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'], $sent)) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back, refresh the page and try again.');
    }
}

if (!defined('CSRF_BUFFER_STARTED')) {
    define('CSRF_BUFFER_STARTED', true);
    $csrfTok = $_SESSION['csrf_token'];
    ob_start(function ($html) use ($csrfTok) {
        if (stripos($html, '<form') === false) { return $html; }
        $field = '<input type="hidden" name="csrf_token" value="' . $csrfTok . '">';
        return preg_replace('/(<form\b[^>]*\bmethod\s*=\s*["\']?post["\']?[^>]*>)/i', '$1' . $field, $html);
    });
}
