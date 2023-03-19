<?php
require_once 'C:\xampp\htdocs\GUVI\vendor\autoload.php';

$firstName = $_POST['fname'];
$lastName = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];

// create a PDO object to connect to the database
$dsn = 'mysql:host=localhost;dbname=auth';
$dbUsername = 'root';
$dbPassword = 'okokokok';
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
$client = new MongoDB\Client('mongodb://localhost:27017');
$database = $client->userinfodb;
$collection = $database->profile;
if (!$collection) {
    $collection = $database->createCollection('profile');
}
$user = [
    'firstname' => $firstName,
    'lastname' => $lastName,
    'email' => $email,
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
$response = [
    'status' => 'succes',
    'message' => 'user created succesfully'
];
echo json_encode($response);
die();

?>