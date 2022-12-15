<?php

$contents = file_get_contents('./tests/Fixtures.php');

$username = $_SERVER['AFRICASTALKING_USERNAME'] ?? '';
$api = $_SERVER['AFRICASTALKING_API_KEY'] ?? '';
$api = $_SERVER['AFRICASTALKING_API_KEY'] ?? '';
$voicePhoneNumber = $_SERVER['AFRICASTALKING_VOICE_PHONE_NUMBER'] ?? '+254711082489';

$contents = str_replace("username = 'sandbox'","username = '{$username}'",$contents);
$contents = str_replace("apiKey = ''","apiKey = '{$api}'",$contents);
$contents = str_replace("voicePhoneNumber = '+254711082489'","voicePhoneNumber = '{$voicePhoneNumber}'",$contents);
//$contents = str_replace("username = 'sandbox'","username = '{$_SERVER['AFRICASTALKING_USERNAME']}'",$contents);
var_dump($contents);

file_put_contents('./tests/Fixtures.php', $contents);