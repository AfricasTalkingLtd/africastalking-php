<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class AirtimeTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = $_ENV['TEST_USERNAME'];
		$this->apiKey 	= $_ENV['TEST_API_KEY'];

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->airtime();		
	}

	public function testSendAirtimeToOne()
	{
		$response = $this->client->send([
			'recipients'	=> [
				['phoneNumber' => '+2348068364334', 'amount' => 'NGN 100'],
			]
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('responses', $response_array);
	}

	public function testSendAirtimeToMany()
	{
		$response = $this->client->send([
			'recipients'	=> [
				['phoneNumber' => '+2348068364334', 'amount' => 'NGN 10'],
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
					['phoneNumber' => '+2348068364334', 'amount' => 'NGN 9'],
				]
			])
		);

		$this->assertArraySubset(
			['status'		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					['phoneNumber' => '+2348068364334', 'amount' => 'NGN 10001'],
				]
			])
		);

		$this->assertArraySubset(
			['status'		=> 'error'],
			$response = $this->client->send([
				'recipients'	=> [
					['phoneNumber' => '+2348068364334', 'amount' => '1000'],
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
					['phoneNumber' => '+2348068364334'],
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