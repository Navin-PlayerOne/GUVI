<?php
require_once 'C:\xampp\htdocs\GUVI\vendor\autoload.php';
require_once 'verify.php';
use Firebase\JWT\JWT;
$token = $_POST['token'];

//if token is empty then return
if(empty($token)){
    $response = [
        'status' => 'failure',
        'message' => 'UnAuthorized'
    ];
    echo json_encode($response);
    die();
}

//if not valid token return
$email = verifyToken($token);
if(empty($email)){
    $response = [
        'status' => 'failure',
        'message' => 'UnAuthorized'
    ];
    echo json_encode($response);
    die();
}

$response = [
    'status' => 'success',
    'message' => 'Authorized',
    'ok' => 'true'
];
echo json_encode($response);
die();


?>