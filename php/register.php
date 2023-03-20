<?php
require_once 'C:\xampp\htdocs\GUVI\vendor\autoload.php';
use Firebase\JWT\JWT;

$env = parse_ini_file('../.env');

$firstName = $_POST['fname'];
$lastName = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];

// create a PDO object to connect to the database
$dsn = $env['MYSQL_URL'];
$dbUsername = $env["MYSQL_USER_NAME"];
$dbPassword = $env["MYSQL_PASSWORD"];
$pdo = new PDO($dsn, $dbUsername, $dbPassword);

// prepare a SQL statement to create the table if it doesn't exist
$sql = 'CREATE TABLE IF NOT EXISTS Auth (
  email VARCHAR(50) NOT NULL PRIMARY KEY,
  id  VARCHAR(30) NOT NULL,
  password VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)';
$pdo->exec($sql);

//check wether the user is already exist or not
$stmt = $pdo->prepare('SELECT COUNT(*) FROM Auth WHERE email = :email');
$stmt->bindValue(':email', $email);
$stmt->execute();
$result = $stmt->fetchColumn();
if ($result > 0) {
    $response = [
        'status' => 'failure',
        'message' => 'user already exist!'
    ];
    echo json_encode($response);
    die();
}

//create user record in mongo db
$client = new MongoDB\Client($env['MONGODB_URL']);
$database = $client->userinfodb;
$collection = $database->profile;
if (!$collection) {
    $collection = $database->createCollection('profile');
}
$user = [
    'firstname' => $firstName,
    'lastname' => $lastName,
    'email' => $email,
    'phone' => $phone
];
$insertResult = $collection->insertOne($user);
$id = $insertResult->getInsertedId();


//if user not exist in db then encrypt password and store in mysql
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO Auth (email, id, password) VALUES (:email, :id, :password)");
$stmt->bindValue(':email', $email);
$stmt->bindValue(':id', $id);
$stmt->bindValue(':password', $hashed_password);
$stmt->execute();

//create and return a jwt to client
$time = time();
$expiration_time = $time + 3600;
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$secret_key = $env['JWT_SECRET'];
$payload = [
    'id' => $id,
    'email' => $email,
    'iat' => $time,
    'exp' => $expiration_time // Set the token to expire in 1 hour

];
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