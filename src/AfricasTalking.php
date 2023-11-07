<?php
namespace AfricasTalking\SDK;

use GuzzleHttp\Client;

class AfricasTalking
{
	const BASE_DOMAIN         = "africastalking.com";
	const BASE_SANDBOX_DOMAIN = "sandbox." . self::BASE_DOMAIN;
	
	protected $username;
	protected $apiKey;

	protected $client;
	protected $voiceClient;
	protected $tokenClient;
	protected $contentClient;
	protected $mobileDataClient;

	public $baseUrl;
	protected $voiceUrl;
	protected $contentUrl;
	protected $mobileDataUrl;

	public function __construct($username, $apiKey)
	{
		if($username === 'sandbox') {
			$this->baseDomain = self::BASE_SANDBOX_DOMAIN;
		} else {
			$this->baseDomain = self::BASE_DOMAIN;
		}

		$this->baseUrl = "https://api." . $this->baseDomain . "/version1/";
		$this->voiceUrl = "https://voice." . $this->baseDomain . "/";
		$this->mobileDataUrl = "https://bundles." . $this->baseDomain . "/";
		$this->contentUrl = ($username === "sandbox") ? ($this->baseUrl) : ("https://content." . $this->baseDomain . "/version1/");
		$this->checkoutTokenUrl = "https://api." . $this->baseDomain . "/";

		if ($username === 'sandbox') {
			$this->contentUrl = $this->baseUrl;
		}

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

		$this->contentClient = new Client([
			'base_uri' => $this->contentUrl,
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

		$this->mobileDataClient = new Client([
			'base_uri' => $this->mobileDataUrl,
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

	public function mobileData()
	{
		$mobileData = new MobileData($this->mobileDataClient, $this->username, $this->apiKey);		
		return $mobileData;
	}

	public function token()
	{
		$token = new Token($this->tokenClient, $this->username, $this->apiKey);
		return $token;
	}
}
