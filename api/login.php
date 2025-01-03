<?php

require './functions.php';

$data = getJsonInput();
$users = loadUsers();

if (!isset($data['username'], $data['password']) || !isset($users[$data['username']])) {
    sendError('Invalid credentials');
}

if (!password_verify($data['password'], $users[$data['username']])) {
    sendError('Incorrect username or password');
}

$accessPayload = [
    'iss' => 'localhost',
    'aud' => 'localhost',
    'iat' => time(),
    'exp' => time() + 3600, // 1 hour expiration
    'username' => $data['username']
];

$accessToken = generateJWT($accessPayload, $sec_key);

$refreshPayload = [
    'iss' => 'localhost',
    'aud' => 'localhost',
    'iat' => time(),
    'exp' => time() + (3600 * 24 * 7), // 1 week expiration
    'username' => $data['username']
];

$refreshToken = generateJWT($refreshPayload, $sec_key);

$tokens = loadTokens();
$tokens[$data['username']] = [
    'access' => $accessToken,
    'refresh' => $refreshToken
];
saveTokens($tokens);

sendSuccess([
    'access_token' => $accessToken, 
    'refresh_token' => $refreshToken
]);
?>
