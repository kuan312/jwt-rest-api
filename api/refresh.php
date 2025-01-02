<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$sec_key = '12345test';
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['refresh_token'])) {
    echo json_encode([
        'type' => 'error',
        'message' => 'Refresh token missing'
    ]);
    exit;
}

$tokensFile = __DIR__ . '/../db/tokens_data.json';
$tokens = file_exists($tokensFile) ? json_decode(file_get_contents($tokensFile), true) : [];

try {
    $decoded = JWT::decode($data['refresh_token'], new Key($sec_key, 'HS256'));
    $username = $decoded->username;

    if (!isset($tokens[$username]) || $tokens[$username]['refresh'] !== $data['refresh_token']) {
        echo json_encode([
            'type' => 'error',
            'message' => 'Invalid refresh token'
        ]);
        exit;
    }

    $accessPayload = [
        'iss' => 'localhost',
        'aud' => 'localhost',
        'iat' => time(),
        'exp' => time() + 3600,
        'username' => $username
    ];

    $newAccessToken = JWT::encode($accessPayload, $sec_key, 'HS256');
    $tokens[$username]['access'] = $newAccessToken;

    file_put_contents($tokensFile, json_encode($tokens));

    echo json_encode(['access_token' => $newAccessToken]);
} catch (Exception $e) {
    echo json_encode([
        'type' => 'error',
        'message' => 'Invalid or expired refresh token'
    ]);
    exit;
}
