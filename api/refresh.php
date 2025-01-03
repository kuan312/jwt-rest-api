<?php

require './functions.php';

$data = getJsonInput();

if (!isset($data['refresh_token'])) {
    sendError('Refresh token missing');
}

$tokens = loadTokens();

try {
    $decoded = decodeJWT($data['refresh_token'], $sec_key);
    $username = $decoded->username;

    if (!isset($tokens[$username]) || $tokens[$username]['refresh'] !== $data['refresh_token']) {
        sendError('Invalid refresh token', 401);
    }

    $accessPayload = [
        'iss' => 'localhost',
        'aud' => 'localhost',
        'iat' => time(),
        'exp' => time() + 3600,
        'username' => $username
    ];

    $newAccessToken = generateJWT($accessPayload, $sec_key);
    $tokens[$username]['access'] = $newAccessToken;
    saveTokens($tokens);

    respondJson(['access_token' => $newAccessToken]);
} catch (Exception $e) {
    sendError('Invalid or expired refresh token', 401);
}
?>
