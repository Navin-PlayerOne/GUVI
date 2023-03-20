<?php
require_once 'C:\xampp\htdocs\GUVI\vendor\autoload.php';
require_once 'verify.php';
use Firebase\JWT\JWT;
$env = parse_ini_file('../.env');

$token = $_POST['token'];
$firstName = $_POST['fname'];
$lastName = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];

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
$emailTok = verifyToken($token);
if(empty($emailTok)){
    $response = [
        'status' => 'failure',
        'message' => 'UnAuthorized'
    ];
    echo json_encode($response);
    die();
}

// create a PDO object to connect to the database
$dsn = $env['MYSQL_URL'];
$dbUsername = $env["MYSQL_USER_NAME"];
$dbPassword = $env["MYSQL_PASSWORD"];
$pdo = new PDO($dsn, $dbUsername, $dbPassword);

//fetch id from mysql using emailid
$stmt = $pdo->prepare("SELECT * FROM Auth WHERE email= :email");
$stmt->bindValue(':email', $emailTok);
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
$client = new MongoDB\Client($env['MONGODB_URL']);
$database = $client->userinfodb;
$collection = $database->profile;
if (!$collection) {
    $collection = $database->createCollection('profile');
}

$result = $collection->updateOne(
    ['_id' =>  new MongoDB\BSON\ObjectID($id)],
    ['$set' => [
        'firstname' => $firstName,
        'lastname' => $lastName,
        'email' => $email,
        'phone' => $phone
    ]]
);
if ($result->getMatchedCount() > 0) {
    //success result
    $userObject = [
        'firstname' => $firstName,
        'lastname' => $lastName,
        'email' => $email,
        'phone' => $phone
    ];
    echo json_encode($userObject);
    die();
} else {
    $response = [
        'status' => 'failure',
        'message' => 'unknown error'
    ];
    echo json_encode($response);
    die();
}

?>