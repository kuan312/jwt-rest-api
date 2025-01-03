<?php

require './functions.php';

$authHeader = getAuthorizationHeader();

if (!$authHeader) {
    sendError('Authorization header missing', 401);
}

$token = str_replace('Bearer ', '', $authHeader);

try {
    $decoded = decodeJWT($token, $sec_key);
    sendSuccess(['username' => $decoded->username, 'issued_at' => $decoded->iat, 'expires_at' => $decoded->exp]);
} catch (Exception $e) {
    sendError('Invalid or expired token', 401);
}
?>
