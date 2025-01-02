<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$sec_key = '12345test';
$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    echo json_encode([
        'type' => 'error',
        'message' => 'Authorization header missing'
    ]);
    exit;
}

$authHeader = $headers['Authorization'];
$token = str_replace('Bearer ', '', $authHeader);

try {
    $decoded = JWT::decode($token, new Key($sec_key, 'HS256'));
    echo json_encode(['type' => 'success', 'data' => $decoded]);
} catch (Exception $e) {
    echo json_encode([
        'type' => 'error',
        'message' => 'Invalid token'
    ]);
    exit;
}
