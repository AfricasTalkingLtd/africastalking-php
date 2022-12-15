<?php

$contents = file_get_contents('./tests/Fixtures.php');

var_dump($contents);
//var_dump($_SERVER['AFRICASTALKING_USERNAME']);
//var_dump($_SERVER['AFRICASTALKING_API_KEY']);

file_put_contents('./tests/Fixtures.php', $contents);