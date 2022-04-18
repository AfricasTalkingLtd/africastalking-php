<?php

namespace AfricasTalking\SDK;
use GuzzleHttp\Client;

class ATClient extends HttpClient
{ 

    public $username;
	public $apiKey;

    protected $client;
	protected $contentClient;
	protected $voiceClient;
	protected $paymentClient;
	protected $tokenClient;

	public $baseUrl;
	protected $contentUrl;
	protected $voiceUrl;
	protected $paymentUrl;

    public function __construct($baseDomain, $username, $apiKey) {
        $this->baseUrl = "https://api." . $baseDomain . "/version1/";
		$this->voiceUrl = "https://voice." . $baseDomain . "/";
		$this->paymentsUrl = "https://payments." . $baseDomain . "/";
		$this->contentUrl = ($username === "sandbox") ? ($this->baseUrl) : ("https://content." . $baseDomain . "/version1/");
		$this->checkoutTokenUrl = "https://api." . $baseDomain . "/";

		if ($username === 'sandbox') {
			$this->contentUrl = $this->baseUrl;
		}

		$this->username = $username;
		$this->apiKey = $apiKey;
    }

    public function base()
    {
        return $this->make($this->baseUrl, 'application/x-www-form-urlencoded');
    }

    public function content()
    {
        return $this->make($this->contentUrl, 'application/x-www-form-urlencoded');
    }

    public function voice()
    {
        return $this->make($this->voiceUrl, 'application/x-www-form-urlencoded');
    }

    public function payments()
    {
        return $this->make($this->paymentsUrl, 'application/json');
    }

    public function token()
    {
        return $this->make($this->checkoutTokenUrl, 'application/json');
    }
}