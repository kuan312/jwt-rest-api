<?php
require '../vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username'], $data['password'])) {
    echo json_encode([
        'type' => 'error',
        'message' => 'Invalid input'
    ]);
    exit;
}

$usersFile = __DIR__ . '/../db/credentials.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

if (isset($users[$data['username']])) {
    echo json_encode([
        'type' => 'error',
        'message' => 'User already exists'
    ]);
    exit;
}

$users[$data['username']] = password_hash($data['password'], PASSWORD_DEFAULT);
file_put_contents($usersFile, json_encode($users));

echo json_encode([
    'type' => 'success',
    'message' => 'User registered successfully'
]);
