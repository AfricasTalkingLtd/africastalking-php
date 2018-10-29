<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class AirtimeTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->airtime();		
	}

	public function testSendAirtimeToOne()
	{
		$response = $this->client->send([
			'recipients'	=> [[
                'phoneNumber' => Fixtures::$phoneNumber,
                'currencyCode' => Fixtures::$currencyCode,
                'amount' => Fixtures::$amount
            ]]
		]);

		$this->assertObjectHasAttribute('responses', $response['data']);
	}

	public function testSendAirtimeToMany()
	{
		$response = $this->client->send([
			'recipients'	=> [[
                'phoneNumber' => Fixtures::$phoneNumber,
                'currencyCode' => Fixtures::$currencyCode,
                'amount' => Fixtures::$amount
            ], [
                'phoneNumber' => '+2347038151149',
                'currencyCode' => 'NGN',
                'amount' => '10000'
            ]]
		]);

		$this->assertObjectHasAttribute('responses', $response['data']);
	}
}
