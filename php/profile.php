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
// create a PDO object to connect to the database
$dsn = 'mysql:host=localhost;dbname=auth';
$dbUsername = 'root';
$dbPassword = 'okokokok';
$pdo = new PDO($dsn, $dbUsername, $dbPassword);

//fetch id from mysql using emailid
$stmt = $pdo->prepare("SELECT * FROM Auth WHERE email= :email");
$stmt->bindValue(':email', $email);
$stmt->execute();
$numRows = $stmt->rowCount();

if ($numRows === 0) {
    $response = [
        'status' => 'failure',
        'message' => 'unknown error'
    ];
    echo json_encode($response);
    die();
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$id;
foreach ($rows as $row) {
    $id = $row['id'];
}

//contact to mongo db
$client = new MongoDB\Client('mongodb://localhost:27017');
$database = $client->userinfodb;
$collection = $database->profile;
if (!$collection) {
    $collection = $database->createCollection('profile');
}

//get document by its id in efficient way
$document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);
$userObject = [
    'firstname' => $document['firstname'],
    'lastname' => $document['lastname'],
    'email' => $document['email'],
    'phone' => $document['phone']
];
echo json_encode($userObject);
die();
?>
