<?php
require __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;
use AfricasTalking\SDK\AfricasTalking;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$username = $_ENV['USERNAME'];
$apiKey = $_ENV['API_KEY'];

$AT = new AfricasTalking($username, $apiKey);

// Router
$router = new AltoRouter();

$router->map( 'GET', '/', function() {
    require __DIR__ . '/views/index.php';
});

$router->map( 'POST', '/auth/register/[*:phone]', function ($phone) {
    global $AT;
    $sms = $AT->sms();
    $response = $sms->send(array(
        "to" => $phone,
        "from" => "AT2FA",
        "message" => "Welcome to Awesome Company",
    ));
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
});

$router->map( 'POST', '/airtime/[*:phone]', function ($phone) {
    global $AT;
    $airtime = $AT->airtime();
    $response = $airtime->send(array(
        "recipients" => array(
            array(
                "phoneNumber" => $phone,
                "amount" => $_GET['amount'],
            )
        )
    ));
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
});

$match = $router->match();
if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
