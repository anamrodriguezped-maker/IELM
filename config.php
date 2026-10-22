<?php
declare(strict_types=1);
// Ajusta estas credenciales si tu instalación local de MySQL usa otras.
const DB_HOST = '127.0.0.1';
const DB_NAME = 'lechuga_bd';
const DB_USER = 'root';
const DB_PASS = '';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_check(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('La sesión del formulario venció. Vuelve atrás y recarga la página.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void
{
    if (!current_user()) { header('Location: iniciar_sesion.php'); exit; }
}
function require_admin(): void
{
    require_login();
    if (current_user()['rol'] !== 'admin') { http_response_code(403); exit('Acceso reservado para administración.'); }
}
function money(float|string $amount): string { return '$' . number_format((float)$amount, 0, ',', '.'); }
