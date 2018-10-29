<?php
namespace AfricasTalking\SDK;

use GuzzleHttp\Client;

class AfricasTalking
{
	const BASE_DOMAIN        = "africastalking.com";
	const BASE_SANDBOX_DOMAIN = "sandbox." . self::BASE_DOMAIN;
	
	protected $username;
	protected $apiKey;

	protected $client;
	protected $paymentClient;
	protected $voiceClient;
	protected $tokenClient;

	public $baseUrl;
	protected $voiceUrl;
	protected $paymentUrl;

	protected $SMS;

	public function __construct($username, $apiKey)
	{
		if($username === 'sandbox') {
			$this->baseDomain = self::BASE_SANDBOX_DOMAIN;
		} else {
			$this->baseDomain = self::BASE_DOMAIN;
		}

		$this->baseUrl = "https://api." . $this->baseDomain . "/version1/";
		$this->voiceUrl = "https://voice." . $this->baseDomain . "/";
		$this->paymentsUrl = "https://payments." . $this->baseDomain . '/';
		$this->checkoutTokenUrl = "https://api." . $this->baseDomain . '/';

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

		$this->voiceClient = new Client([
			'base_uri' => $this->voiceUrl,
			'headers' => [
				'apikey' => $this->apiKey,
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Accept' => 'application/json'
			]
		]);

		$this->paymentsClient = new Client([
			'base_uri' => $this->paymentsUrl,
			'headers' => [
				'apikey' => $this->apiKey,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json'
			]
		]);

		$this->tokenClient = new Client([
			'base_uri' => $this->checkoutTokenUrl,
			'headers' => [
				'apikey' => $this->apiKey,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json'
			]
		]);
	}

	public function sms()
	{
		$sms = new SMS($this->client, $this->username, $this->apiKey);
		return $sms;
	}

	public function airtime()
	{
		$airtime = new Airtime($this->client, $this->username, $this->apiKey);		
		return $airtime;
	}

	public function voice()
	{
		$voice = new Voice($this->voiceClient, $this->username, $this->apiKey);
		return $voice;
	}

	public function application()
	{
		$application = new Application($this->client, $this->username, $this->apiKey);		
		return $application;
	}

	public function payments()
	{
		$payments = new Payments($this->paymentsClient, $this->username, $this->apiKey);		
		return $payments;
	}

	public function token()
	{
		$token = new Token($this->tokenClient, $this->username, $this->apiKey);
		return $token;
	}
}
