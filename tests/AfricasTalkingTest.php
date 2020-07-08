<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class AfricasTalkingTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$this->client 	= new AfricasTalking($this->username, $this->apiKey);
	}
	
	public function testSMSClass()
	{
		$this->assertInstanceOf(\AfricasTalking\SDK\SMS::class, $this->client->sms());
	}

	public function testContentClass()
	{
		$this->assertInstanceOf(\AfricasTalking\SDK\Content::class, $this->client->content());
	}

	public function testAirtimeClass()
	{
		$this->assertInstanceOf(\AfricasTalking\SDK\Airtime::class, $this->client->airtime());
	}
	
	public function testVoiceClass()
	{
		$this->assertInstanceOf(\AfricasTalking\SDK\Voice::class, $this->client->voice());
	}
	
	public function testApplicationClass()
	{
		$this->assertInstanceOf(\AfricasTalking\SDK\Application::class, $this->client->application());
	}
	
	public function testPaymentsClass()
	{
		$this->assertInstanceOf(\AfricasTalking\SDK\Payments::class, $this->client->payments());
	}
}
