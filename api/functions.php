<?php

require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$sec_key = '12345test';

function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true);
}

function respondJson($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sendError($message, $status = 400) {
    http_response_code($status);
    respondJson([
        'type' => 'error',
        'message' => $message
    ]);
}

function sendSuccess($data) {
    respondJson([
        'type' => 'success',
        'data' => $data
    ]);
}

function loadUsers() {
    $usersFile = __DIR__ . '/../db/credentials.json';
    return file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
}

function saveUsers($users) {
    $usersFile = __DIR__ . '/../db/credentials.json';
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
}

function loadTokens() {
    $tokensFile = __DIR__ . '/../db/tokens_data.json';
    return file_exists($tokensFile) ? json_decode(file_get_contents($tokensFile), true) : [];
}

function saveTokens($tokens) {
    $tokensFile = __DIR__ . '/../db/tokens_data.json';
    file_put_contents($tokensFile, json_encode($tokens, JSON_PRETTY_PRINT));
}

function generateJWT($payload, $secret, $alg = 'HS256') {
    return JWT::encode($payload, $secret, $alg);
}

function decodeJWT($token, $secret, $alg = 'HS256') {
    return JWT::decode($token, new Key($secret, $alg));
}

function getAuthorizationHeader() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        return $headers['Authorization'];
    }
    return null;
}
?>
