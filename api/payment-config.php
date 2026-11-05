<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

echo json_encode([
    'stripe' => TT_STRIPE_SECRET_KEY !== '',
], JSON_UNESCAPED_UNICODE);
