<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;

$sec_key = '12345test';

$data = json_decode(file_get_contents('php://input'), true);
$usersFile = __DIR__ . '/../db/credentials.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

if (!isset($data['username'], $data['password']) || !isset($users[$data['username']])) {
    echo json_encode([
        'type' => 'error',
        'message' => 'Invalid credentials'
    ]);
    exit;
}

if (!password_verify($data['password'], $users[$data['username']])) {
    echo json_encode(
        [
            'type'=>'error',
            'message' => 'Incorrect username or password'
    ]);
    exit;
}

$accessPayload = [
    'iss' => 'localhost',
    'aud' => 'localhost',
    'iat' => time(),
    'exp' => time() + 3600,
    'username' => $data['username']
];

$accessToken = JWT::encode($accessPayload, $sec_key, 'HS256');

$refreshPayload = [
    'iss' => 'localhost',
    'aud' => 'localhost',
    'iat' => time(),
    'exp' => time() + 3600 * 24 * 7,
    'username' => $data['username']
];

$refreshToken = JWT::encode($refreshPayload, $sec_key, 'HS256');

$tokensFile = __DIR__ . '/../db/tokens_data.json';
$tokens = file_exists($tokensFile) ? json_decode(file_get_contents($tokensFile), true) : [];
$tokens[$data['username']] = [
    'access' => $accessToken,
    'refresh' => $refreshToken
];
file_put_contents($tokensFile, json_encode($tokens));

echo json_encode([
    'type' => 'success',
    'data' => [
        'access_token' => $accessToken, 
        'refresh_token' => $refreshToken
    ]
    
]);
