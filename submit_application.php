<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$fullName = sanitizeText($_POST['fullName'] ?? '');
$email = sanitizeEmail($_POST['email'] ?? '');
$phone = sanitizeText($_POST['phone'] ?? '');
$position = sanitizeText($_POST['position'] ?? '');
$coverLetter = sanitizeText($_POST['coverLetter'] ?? '');
$termsAccepted = isset($_POST['termsCheck']) ? 1 : 0;

if ($fullName === '' || $email === '' || $phone === '' || $position === '' || $termsAccepted !== 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please complete all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$cvFileName = null;
$cvPath = null;
if (isset($_FILES['cvUpload']) && $_FILES['cvUpload']['error'] === UPLOAD_ERR_OK) {
    $cvInfo = $_FILES['cvUpload'];
    if (!in_array($cvInfo['type'], $allowedTypes, true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Only PDF and Word documents are allowed for the CV.']);
        exit;
    }

    if ($cvInfo['size'] > 5 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'CV must be 5MB or smaller.']);
        exit;
    }

    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($cvInfo['name']));
    $targetPath = $uploadDir . uniqid('cv_', true) . '_' . $safeName;
    if (!move_uploaded_file($cvInfo['tmp_name'], $targetPath)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to upload the CV.']);
        exit;
    }
    $cvFileName = $safeName;
    $cvPath = str_replace('\\', '/', $targetPath);
}

$supportingFiles = [];
if (isset($_FILES['certUpload']) && !empty($_FILES['certUpload']['name'][0])) {
    foreach ($_FILES['certUpload']['name'] as $index => $name) {
        if ($_FILES['certUpload']['error'][$index] !== UPLOAD_ERR_OK) {
            continue;
        }
        $tmpName = $_FILES['certUpload']['tmp_name'][$index];
        $size = $_FILES['certUpload']['size'][$index];
        if ($size > 10 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Supporting documents must be 10MB or smaller each.']);
            exit;
        }
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($name));
        $targetPath = $uploadDir . uniqid('doc_', true) . '_' . $safeName;
        if (!move_uploaded_file($tmpName, $targetPath)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to upload supporting documents.']);
            exit;
        }
        $supportingFiles[] = $safeName;
    }
}

$stmt = $pdo->prepare('INSERT INTO applications (full_name, email, phone, position, cover_letter, cv_filename, cv_path, supporting_documents, terms_accepted, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([
    $fullName,
    $email,
    $phone,
    $position,
    $coverLetter,
    $cvFileName,
    $cvPath,
    implode(', ', $supportingFiles),
    $termsAccepted,
    $_SERVER['REMOTE_ADDR'] ?? null,
    $_SERVER['HTTP_USER_AGENT'] ?? null,
]);

echo json_encode(['success' => true, 'message' => 'Application submitted successfully.']);
