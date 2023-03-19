<?php
require_once 'C:\xampp\htdocs\GUVI\vendor\autoload.php';
use Firebase\JWT\JWT;

$email = $_POST['email'];
$password = $_POST['password'];

// create a PDO object to connect to the database
$dsn = 'mysql:host=localhost;dbname=auth';
$dbUsername = 'root';
$dbPassword = 'okokokok';
$pdo = new PDO($dsn, $dbUsername, $dbPassword);




//check wether the user is already exist or not if
$stmt = $pdo->prepare("SELECT * FROM Auth WHERE email= :email");
$stmt->bindValue(':email', $email);
$stmt->execute();
$numRows = $stmt->rowCount();

if ($numRows === 0) {
    $response = [
        'status' => 'failure',
        'message' => 'Invalid credentiald'
    ];
    echo json_encode($response);
    die();
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$id;
$hashed_password;
foreach ($rows as $row) {
    $id = $row['id'];
    $hashed_password = $row['password'];
   
}
//if credentials are not valid return
if (!password_verify($password, $hashed_password)) {
    $response = [
        'status' => 'failure',
        'message' => 'Invalid credentials'
    ];
    echo json_encode($response);
    die();
}
//if everything is correct
//create and return a jwt to client
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$secret_key = 'jsh7483yj4ljer54dsbjksd@#^$jbjsh';
$payload = [
    'id' => $id,
    'email' => $email
];
$expiration_time = 3600;
$token = JWT::encode($payload, $secret_key,'HS256');
// Store the token in Redis with an expiration time
$redis->setex($token, $expiration_time, $email);
$response = [
    'status' => 'succes',
    'message' => 'login succesfully',
    'token' => $token
];
echo json_encode($response);
die();


?>