<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
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
			'recipients'	=> [
				['phoneNumber' => Fixtures::$phoneNumber, 'amount' => Fixtures::$airtimeAmount],
			]
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('responses', $response_array);
	}

	public function testSendAirtimeToMany()
	{
		$response = $this->client->send([
			'recipients'	=> [
				['phoneNumber' => Fixtures::$phoneNumber, 'amount' => Fixtures::$airtimeAmount],
				['phoneNumber' => '+2347038151149', 'amount' => 'NGN 10000'],
			]
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('responses', $response_array);
	}

	public function testSendAirtimeToWrongAmounts()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					[Fixtures::$phoneNumber, 'amount' => 'KES 9'],
				]
			])
		);

		$this->assertArraySubset(
			['status'		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					['phoneNumber' => Fixtures::$phoneNumber, 'amount' => 'KES 10001'],
				]
			])
		);

		$this->assertArraySubset(
			['status'		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					['phoneNumber' => Fixtures::$phoneNumber, 'amount' => '1000'],
				]
			])
		);
	}

	public function testSendAirtimeWithMissingParams()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					['phoneNumber' => Fixtures::$phoneNumber],
				]
			])
		);

		$this->assertArraySubset(
			['status'		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					['amount' => 'NGN 1000'],
				]
			])
		);
	}
}