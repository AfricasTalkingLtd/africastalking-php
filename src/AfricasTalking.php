<?php
namespace AfricasTalking\SDK;

use GuzzleHttp\Client;

class AfricasTalking
{
	const BASE_DOMAIN = "africastalking.com";
	const BASE_SANDBOX_DOMAIN = "sandbox." . self::BASE_DOMAIN;

	protected $atClient;

	public function __construct($username, $apiKey)
	{
		if ($username === 'sandbox') {
			$this->baseDomain = self::BASE_SANDBOX_DOMAIN;
		}
		else {
			$this->baseDomain = self::BASE_DOMAIN;
		}

		$this->atClient = new ATClient($this->baseDomain, $username, $apiKey);
	}

	public function sms()
	{
		$content = new Content($this->atClient);
		$sms = new SMS($this->atClient, $content);
		return $sms;
	}

	public function content()
	{
		$content = new Content($this->atClient);
		return $content;
	}

	public function airtime()
	{
		$airtime = new Airtime($this->atClient);
		return $airtime;
	}

	public function voice()
	{
		$voice = new Voice($this->atClient);
		return $voice;
	}

	public function application()
	{
		$application = new Application($this->atClient);
		return $application;
	}

	public function payments()
	{
		$payments = new Payments($this->atClient);
		return $payments;
	}

	public function token()
	{
		$token = new Token($this->atClient);
		return $token;
	}
}
