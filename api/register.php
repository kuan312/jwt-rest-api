<?php

require './functions.php';

$data = getJsonInput();

if (!isset($data['username'], $data['password'])) {
    sendError('Invalid input');
}

$users = loadUsers();

if (isset($users[$data['username']])) {
    sendError('User already exists');
}

$users[$data['username']] = password_hash($data['password'], PASSWORD_DEFAULT);
saveUsers($users);

sendSuccess(['message' => 'User registered successfully']);
?>
