<?php
require_once __DIR__ . '/db.php';

$sql = file_get_contents(__DIR__ . '/schema.sql');
if ($sql === false) {
    echo json_encode(['success' => false, 'message' => 'Schema file not found.']);
    exit;
}

try {
    $pdo->exec($sql);
    echo json_encode(['success' => true, 'message' => 'Database schema initialized successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
