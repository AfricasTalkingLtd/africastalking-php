<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = $_ENV['TEST_USERNAME'];
		$this->apiKey 	= $_ENV['TEST_API_KEY'];

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->payments();		
	}

	public function testDummy()
	{
		$this->assertTrue(true);
	}
}