<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$secret = TT_STRIPE_SECRET_KEY;
if ($secret === '') {
    http_response_code(503);
    echo json_encode(['error' => 'Card payment is not available on the site yet. Please choose PromptPay.']);
    exit;
}

$raw = file_get_contents('php://input') ?: '';
$body = json_decode($raw, true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$amount = (float) ($body['total'] ?? 0);
$name = trim((string) ($body['packageName'] ?? 'Suwan Travel booking'));
$ref = preg_replace('/[^A-Za-z0-9\-]/', '', (string) ($body['ref'] ?? ''));
if ($amount < 20) {
    http_response_code(400);
    echo json_encode(['error' => 'Amount is too small to charge.']);
    exit;
}

$satang = (int) round($amount * 100);
$origin = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/api/stripe-checkout.php')), '/\\');
if ($base === '' || $base === '.') $base = '';
$success = $origin . $base . '/payment-success.html?ref=' . rawurlencode($ref);
$cancel = $origin . $base . '/checkout.html?cancelled=1';

$fields = [
    'mode' => 'payment',
    'success_url' => $success,
    'cancel_url' => $cancel,
    'client_reference_id' => $ref,
    'line_items[0][quantity]' => 1,
    'line_items[0][price_data][currency]' => 'thb',
    'line_items[0][price_data][unit_amount]' => $satang,
    'line_items[0][price_data][product_data][name]' => function_exists('mb_substr') ? mb_substr($name, 0, 120) : substr($name, 0, 120),
];

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_USERPWD => $secret . ':',
    CURLOPT_POSTFIELDS => http_build_query($fields),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
]);
$response = curl_exec($ch);
$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode((string) $response, true);
if ($status >= 400 || empty($data['url'])) {
    http_response_code(502);
    echo json_encode(['error' => $data['error']['message'] ?? 'Could not start card checkout.']);
    exit;
}

echo json_encode(['url' => $data['url']]);
