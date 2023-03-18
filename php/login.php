<?php
// create a PDO object to connect to the database
// $dsn = 'mysql:host=localhost;dbname=mydatabase';
// $username = 'myusername';
// $password = 'mypassword';
// $pdo = new PDO($dsn, $username, $password);

// // prepare a SQL statement to create the table if it doesn't exist
// $sql = 'CREATE TABLE IF NOT EXISTS users (
//   id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//   name VARCHAR(30) NOT NULL,
//   email VARCHAR(50) NOT NULL,
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// )';

// // execute the SQL statement
// $pdo->exec($sql);

// // output a success message

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
echo "Connection to server successfully";
$redis->set("test_key", "Hello World!");
echo "Stored string in redis: " . $redis->get("test_key");



?>