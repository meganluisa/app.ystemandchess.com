<?php 
// Allow Cross Origin Requests (other ips can request data)
header("Access-Control-Allow-Origin: *");

// Load the JWT library
require_once __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

// Random Key. Needs to be Changed Later
$jwt = htmlspecialchars_decode($_GET["jwt"]);
$credentials = json_decode(include "verifyNoEcho.php");

if($credentials == "Error: 405. This key has been tampered with or is out of date." || $credentials == "Error: 406. Please Provide a JSON Web Token.") {
    echo json_encode($credentials);
    return json_encode($credentials);
}
$client = new MongoDB\Client('mongodb+srv://userAdmin:uUmrCVqTypLPq1Hi@cluster0-rxbrl.mongodb.net/test?retryWrites=true&w=majority');
    // Select the user collection
$collection = $client->ystem->users;

if(!is_null($collection->findOne(['username' => $credentials->username]))) {
    $collection->deleteOne(['username' => $credentials->username]);

    if ($credentials->role == "student") {
        $collection->updateOne(['username' => $credentials->parentUsername],[
            '$pull' =>
                [
                    'children' => $username
                ]
            ]
        );
    }

    if ($credentials->role == "parent") {
        $collection->updateMany(['parentUsername' => $credentials->username],[
            '$set' =>
                [
                    'parentUsername' => NULL
                ]
            ]
        );
    }
} else {
    echo "Failure. Document does not exist.";
    return;
}

echo "Sucess";

?>