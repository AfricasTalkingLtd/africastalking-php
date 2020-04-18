<?php
require 'vendor/autoload.php';

use AfricasTalking\SDK\AfricasTalking;

$username = "sandbox";
$apiKey = getenv("API_KEY");

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

$router->map( 'POST', '/mobile/checkout/[*:phone]', function ($phone) {
    global $AT;
    $payments = $AT->payments();
    $response = $payments->mobileCheckout(array(
        "productName" => "TestProduct",
        "phoneNumber" => $phone,
        "currencyCode" => explode(" ", $_GET["amount"])[0],
        "amount" => explode(" ", $_GET["amount"])[1]
    ));
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
});

$router->map( 'POST', '/mobile/b2c/[*:phone]', function ($phone) {
    global $AT;
    $payments = $AT->payments();
    $response = $payments->mobileB2C(array(
        "productName" => "TestProduct",
        "recipients" => array(
            array(
                "phoneNumber" => $phone,
                "currencyCode" => explode(" ", $_GET["amount"])[0],
                "amount" => explode(" ", $_GET["amount"])[1],
                "name" => "Test Guy",
                "metadata" => array(
                    "nothing" => "no data"
                )
            )
        ),

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
