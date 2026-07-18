<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = sanitizeEmail($_POST['email'] ?? '');
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO subscribers (email, ip_address, user_agent) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE email = email');
$stmt->execute([$email, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);

echo json_encode(['success' => true, 'message' => 'Subscription saved successfully.']);
