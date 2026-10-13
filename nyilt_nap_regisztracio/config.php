<?php
declare(strict_types=1);

/*
 * Helyi fejlesztéshez állítsd be az adatbázis adatait.
 * Éles publikálás előtt változtasd meg az admin jelszót és a titkos kulcsot!
 */
const DB_HOST = '127.0.0.1';
const DB_NAME = 'nyilt_nap';
const DB_USER = 'root';
const DB_PASS = '';

const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'ValtoztasdMeg123!'; // Ezt mindenképp változtasd meg!
const APP_NAME = 'Nyílt Nap';
const SCHOOL_NAME = 'Iskolánk';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Érvénytelen kérés. Frissítsd az oldalt, majd próbáld újra.');
    }
}

function require_admin(): void {
    if (empty($_SESSION['is_admin'])) {
        header('Location: login.php');
        exit;
    }
}
