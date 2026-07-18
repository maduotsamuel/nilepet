<?php
// Database configuration
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname = $_ENV['DB_NAME'] ?? 'nilepet_db';
$dbuser = $_ENV['DB_USER'] ?? 'root';
$dbpass = $_ENV['DB_PASS'] ?? '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $dbuser, $dbpass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please contact the administrator.'
    ]));
}

function generateSalt(): string {
    return bin2hex(random_bytes(16));
}

function hashPassword(string $password, string $salt): string {
    return hash('sha256', $password . ':' . $salt);
}

function sanitizeText(?string $value): string {
    return trim(strip_tags((string) $value));
}

function sanitizeEmail(?string $value): string {
    return filter_var(trim((string) $value), FILTER_SANITIZE_EMAIL);
}

function isStrongPassword(string $password): bool {
    return strlen($password) >= 12
        && preg_match('/[A-Z]/', $password)
        && preg_match('/[a-z]/', $password)
        && preg_match('/[0-9]/', $password)
        && preg_match('/[^A-Za-z0-9]/', $password);
}
