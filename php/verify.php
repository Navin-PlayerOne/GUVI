<?php
require_once 'C:\xampp\htdocs\GUVI\vendor\autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;

function verifyToken($token){
// Connect to Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// Set the JWT secret key
$key = 'jsh7483yjjbjsh';
try {
    // Verify the token signature
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    // echo $decoded;
    //echo var_dump($decoded);
    // Validate the token

    // Check if the token is stored in Redis
    if ($redis->exists($token)) {
        // If the token exists, get the username from Redis
        $email = $redis->get($token);

        // Return the username as JSON to the client
        return $email;
    } else {
        // If the token does not exist, return an error message as JSON to the client
        //return "no token";
    }
}
catch (SignatureInvalidException $e) {
    //return "JWT signature verification failed";
    // provided JWT signature verification failed.
} catch (BeforeValidException $e) {
    //return "before time";
    // provided JWT is trying to be used before "nbf" claim OR
    // provided JWT is trying to be used before "iat" claim.
} catch (ExpiredException $e) {
    //return "expired";
    // provided JWT is trying to be used after "exp" claim.
} catch(Exception $e){
    
}
}
?>