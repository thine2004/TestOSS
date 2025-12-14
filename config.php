<?php
// Config for Database
define('DB_HOST', 'sql306.infinityfree.com');
define('DB_USER', 'if0_40650282');
define('DB_PASS', 'Thitruong123');
define('DB_NAME', 'if0_40650282_english_practice');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();
define('BASE_URL', '/');

// Helpers
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit;
}
function setFlash($type, $msg)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
}
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
