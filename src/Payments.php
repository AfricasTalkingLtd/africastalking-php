<?php
namespace AfricasTalking;

use GuzzleHttp\Client;

class Payments extends Service
{
	public function __construct($client, $username, $apiKey)
	{
		$baseUrl = "http://payments.africastalking.com/";

		if($username === 'sandbox')
			$this->baseUrl = "http://payments.sandbox.africastalking.com/";

		$this->username = $username;
		$this->apiKey = $apiKey;

		$this->client = new Client([
			'base_uri' => $baseUrl,
			'headers' => [
				'apikey' => $this->apiKey,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json'
			]
		]);
	}
	
}