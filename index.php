<?php

header('Content-Type: application/json');
$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, '/api/login') === 0) {
    require './api/login.php';
} elseif (strpos($requestUri, '/api/register') === 0) {
    require './api/register.php';
} elseif (strpos($requestUri, '/api/protected') === 0) {
    require './api/protected.php';
} elseif (strpos($requestUri, '/api/refresh') === 0) {
    require './api/refresh.php';
} else {
    http_response_code(404);
    echo json_encode(['message' => 'API not found']);
}
?>
