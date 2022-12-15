<?php

$contents = file_get_contents('./tests/Fixtures.php.tpl');

$username = $_SERVER['AFRICASTALKING_USERNAME'] ?? '';
$api = $_SERVER['AFRICASTALKING_API_KEY'] ?? '';
$voicePhoneNumber = $_SERVER['AFRICASTALKING_VOICE_PHONE_NUMBER'] ?? '+254711082489';
$alphanumeric = $_SERVER['AFRICASTALKING_ALPHANUMERIC'] ?? null;
$short_code = $_SERVER['AFRICASTALKING_PREMIUM_SHORTCODE'] ?? null;
$payment_product = $_SERVER['AFRICASTALKING_PAYMENT_PRODUCT'] ?? null;

$contents = str_replace("username = 'sandbox'","username = '{$username}'",$contents);
$contents = str_replace("apiKey = ''","apiKey = '{$api}'",$contents);
$contents = str_replace("voicePhoneNumber = '+254711082489'","voicePhoneNumber = '{$voicePhoneNumber}'",$contents);
$contents = str_replace("voicePhoneNumber2 = '+254711082489'","voicePhoneNumber2 = '{$voicePhoneNumber}'",$contents);
$contents = str_replace("alphanumeric = 'Test'","alphanumeric = '{$alphanumeric}'",$contents);
$contents = str_replace("shortCode = ''","shortCode = '{$short_code}'",$contents);
$contents = str_replace("productName = 'Test Product'","productName = '{$payment_product}'",$contents);
var_dump($contents);

file_put_contents('./tests/Fixtures.php', $contents);