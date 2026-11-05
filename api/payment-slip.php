<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$ref = preg_replace('/[^A-Za-z0-9\-]/', '', (string) ($_POST['ref'] ?? ''));
$method = preg_replace('/[^a-z]/', '', (string) ($_POST['method'] ?? ''));
if ($ref === '' || !in_array($method, ['card', 'transfer', 'promptpay'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Choose a payment method before attaching the slip.']);
    exit;
}
if (!isset($_FILES['slip']) || !is_uploaded_file($_FILES['slip']['tmp_name'] ?? '')) {
    http_response_code(400);
    echo json_encode(['error' => 'Attach the payment slip before submitting.']);
    exit;
}

$file = $_FILES['slip'];
if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'The slip could not be uploaded.']);
    exit;
}
if (($file['size'] ?? 0) < 32 || ($file['size'] ?? 0) > 8 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'The slip must be between a few bytes and 8 MB.']);
    exit;
}

$ext = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'pdf' => 'application/pdf'];
if (!isset($allowed[$ext])) {
    http_response_code(400);
    echo json_encode(['error' => 'Use a JPG, PNG, WEBP, or PDF slip.']);
    exit;
}
$mime = function_exists('mime_content_type') ? (string) mime_content_type($file['tmp_name']) : $allowed[$ext];
if ($mime !== $allowed[$ext] && !($ext === 'jpg' && $mime === 'image/pjpeg')) {
    http_response_code(400);
    echo json_encode(['error' => 'That file is not a valid slip.']);
    exit;
}

$dir = dirname(__DIR__) . '/data/orders/' . $ref;
if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not store the slip.']);
    exit;
}

$target = $dir . '/slip.' . ($ext === 'jpeg' ? 'jpg' : $ext);
if (!move_uploaded_file($file['tmp_name'], $target)) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not store the slip.']);
    exit;
}

$meta = [
    'ref' => $ref,
    'method' => $method,
    'total' => (string) ($_POST['total'] ?? ''),
    'packageName' => substr(trim((string) ($_POST['packageName'] ?? '')), 0, 180),
    'name' => substr(trim((string) ($_POST['name'] ?? '')), 0, 120),
    'phone' => substr(trim((string) ($_POST['phone'] ?? '')), 0, 40),
    'date' => substr(trim((string) ($_POST['date'] ?? '')), 0, 20),
    'slip' => basename($target),
    'created' => gmdate('c'),
];
file_put_contents($dir . '/order.json', json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo json_encode(['ok' => true, 'ref' => $ref]);
