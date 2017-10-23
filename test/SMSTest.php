<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class SMSTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = $_ENV['TEST_USERNAME'];
		$this->apiKey 	= $_ENV['TEST_API_KEY'];

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->sms();		
	}

	public function testSMSWithEmptyMessage()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$this->client->send([
				'to' 		=> ['+2348068364334', '+2347038157749'], 
			])
		);
	}

	public function testSMSWithEmptyRecipient()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$this->client->send([
				'message' 	=> 'Testing...'
			])
		);
	}

	public function testSingleSMSSending()
	{
		$response = $this->client->send([
			'to' 		=> ['+2348068364334'], 
			'message' 	=> 'Testing...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testMultipleSMSSending()
	{
		$response = $this->client->send([
			'to' 		=> ['+2348068364334', '+2347038157749'], 
			'message' 	=> 'Testing...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testSMSSendingWithShortcode()
	{
		$response = $this->client->send([
			'to' 		=> ['+2348068364334'], 
			'message' 	=> 'Testing...',
			'from'		=> '12345'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testSMSSendingWithAlphanumeric()
	{
		$response = $this->client->send([
			'to' 		=> ['+2348068364334'], 
			'message' 	=> 'Testing...',
			'from'		=> 'TEST'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testBulkSMSSending()
	{
		$response = $this->client->sendBulk([
			'to' 		=> ['+2348068364334', '+2347038157749'], 
			'message' 	=> 'Testing...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testPremiumSMSWithoutKeyword()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->sendPremium([
				'to' 		=> ['+2348068364334', '+2347038157749'], 
				'linkId'	=> 'messageLinkId',
				'from'		=> '12345',
				'message' 	=> 'Testing...'
			])
		);
	}

	public function testPremiumSMSWithoutLinkId()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->sendPremium([
				'to' 		=> ['+2348068364334', '+2347038157749'], 
				'keyword'	=> 'Test',
				'from'		=> '12345',
				'message' 	=> 'Testing...'
			])
		);
	}

	public function testPremiumSMSWithoutSender()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->sendPremium([
				'to' 		=> ['+2348068364334', '+2347038157749'], 
				'linkId'	=> 'messageLinkId',
				'keyword'	=> 'Test',
				'message' 	=> 'Testing...'
			])
		);
	}

	public function testPremiumSMSSending()
	{
		$response = $this->client->sendPremium([
			'to' 		=> ['+2348068364334', '+2347038157749'], 
			'linkId'	=> 'messageLinkId',
			'keyword'	=> 'Test',
			'from'		=> '12345',
			'message' 	=> 'Testing Premium...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testFetchSMS()
	{
		$response = $this->client->fetchMessages();

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testFetchSMSWithLastReceived()
	{
		$response = $this->client->fetchMessages(['lastReceivedId' => '8796']);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testFetchSMSWithNonNumericLastReceived()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->fetchMessages(['lastReceivedId' => 'iyhbcw'])
		);
	}

	public function testCreateSubscriptionWithMissingPhoneNumber()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->createSubscription([
				'shortCode'		=> '12345',
				'keyword'		=> 'Test'
			])
		);
	}

	public function testCreateSubscriptionWithMissingShortCode()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->createSubscription([
				'phoneNumber' 	=> '+2348068364334',
				'keyword'		=> 'Test'
			])
		);
	}

	public function testCreateSubscriptionWithMissingKeyword()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->createSubscription([
				'phoneNumber' 	=> '+2348068364334',
				'shortCode'		=> '12345'
			])
		);
	}

	public function testCreateSubscription()
	{
		$response = $this->client->createSubscription([
			'phoneNumber' 	=> '+2348068364334',
			'shortCode'		=> '12345',
			'keyword'		=> 'Test'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArraySubset(
			['status' => 'Success'],
			$response_array
		);
	}

	public function testDeleteSubscription()
	{
		$response = $this->client->deleteSubscription([
			'phoneNumber' 	=> '+2348068364334', 
			'shortCode'		=> '12345',
			'keyword'		=> 'Test'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArraySubset(
			['status' => 'Success'],
			$response_array
		);
	}

	public function testFetchSubscriptions()
	{
		$response = $this->client->fetchSubscriptions([
			'shortCode'		=> '12345',
			'keyword'		=> 'Test'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('responses', $response_array);
	}
}