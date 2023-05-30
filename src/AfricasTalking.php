<?php
namespace AfricasTalking\SDK;

use GuzzleHttp\Client;

class AfricasTalking
{
	protected $baseDomain;

	protected $username;
	protected $apiKey;

	protected $client;
	protected $contentClient;
	protected $voiceClient;
	protected $paymentClient;
	protected $tokenClient;

	public $baseUrl;
	protected $contentUrl;
	protected $voiceUrl;
	protected $paymentsUrl; 
	protected $checkoutTokenUrl;

	public function __construct($username, $apiKey)
	{
		$this->username = $username;
		$this->apiKey = $apiKey;

		$this->baseUrl = "https://api." . $this->base_domain() . "/version1/";
		$this->voiceUrl = "https://voice." . $this->base_domain(). "/";
		$this->paymentsUrl = "https://payments." . $this->base_domain() . "/";
		$this->contentUrl = "https://content." . $this->base_domain() . "/version1/";
		$this->checkoutTokenUrl = "https://api." . $this->base_domain() . "/";

		$this->client = new Client($this->client_request($this->baseUrl));

		$this->contentClient = new Client($this->client_request($this->contentUrl));

		$this->voiceClient = new Client($this->client_request($this->voiceUrl));

		$this->paymentsClient = new Client($this->client_request($this->paymentsUrl));

		$this->tokenClient = new Client($this->client_request($this->checkoutTokenUrl));
	}

	public function base_domain(){
		define(BASE_DOMAIN, "africastalking.com");
		define(BASE_SANDBOX_DOMAIN, "sandbox." . self::BASE_DOMAIN);

		if($this->username === 'sandbox') {
			$this->baseDomain = self::BASE_SANDBOX_DOMAIN;
		} else {
			$this->baseDomain = self::BASE_DOMAIN;
		}

		return $this->baseDomain;
	}

	public function client_request($url) {
		return [
			'base_uri' => $url,
			'headers' => [
				'apikey' => $this->apiKey,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json'
			]
		];
	}

	public function sms()
	{
		$content = new Content($this->contentClient, $this->username, $this->apiKey);
		$sms = new SMS($this->client, $this->username, $this->apiKey, $content);
		return $sms;
	}

	public function content()
	{
		$content = new Content($this->contentClient, $this->username, $this->apiKey);
		return $content;
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
