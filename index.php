<?php
require './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$sec_key = '12345test';
$payload = array(
    'isd' => 'localhost',
    'aud' => 'localhost',
    'username' => 'test',
    'password' => 'test',
);

$encode = JWT::encode($payload, $sec_key, 'HS256');
// echo $encode;


$headers = apache_request_headers();
// print_r($headers);
if ($headers['Authorization']) {
    $headerAuth = $headers['Authorization'];
    $decode = JWT::decode($headerAuth, new Key($sec_key, 'HS256'));
    print_r($decode);
}




