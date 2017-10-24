<?php

namespace AfricasTalking;

use GuzzleHttp\Client;

class AfricasTalking
{
	protected $username;
	protected $apiKey;

	protected $client;

	protected $SMS;

	public function __construct($username, $apiKey)
	{

		$this->baseUrl = "http://api.africastalking.com/version1/";

		if($username === 'sandbox')
			$this->baseUrl = "http://api.sandbox.africastalking.com/version1/";

		$this->username = $username;
		$this->apiKey = $apiKey;

		$this->client = new Client([
			'base_uri' => $this->baseUrl,
			'headers' => [
				'apikey' => $this->apiKey,
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Accept' => 'application/json'
			]
		]);
	}

	public function sms()
	{
		$sms = new SMS($this->client, $this->username, $this->apiKey);		
		return $sms;
	}

	public function ussd()
	{
		$ussd = new USSD($this->client, $this->username, $this->apiKey);		
		return $ussd;
	}

	public function airtime()
	{
		$airtime = new Airtime($this->client, $this->username, $this->apiKey);		
		return $airtime;
	}

	public function voice()
	{
		$voice = new Voice($this->client, $this->username, $this->apiKey);		
		return $voice;
	}

	public function account()
	{
		$account = new Account($this->client, $this->username, $this->apiKey);		
		return $account;
	}

	public function payments()
	{
		$payments = new Payments($this->client, $this->username, $this->apiKey);		
		return $payments;
	}


}