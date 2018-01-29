<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class SMSTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->sms();
	}

	public function testSMSWithEmptyMessage()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$this->client->send([
				'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
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
			'to' 		=> [Fixtures::$phoneNumber], 
			'message' 	=> 'Testing SMS...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testMultipleSMSSending()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing multiple sending...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testSMSSendingWithShortcode()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with short code...',
			'from'		=> '12345'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testSMSSendingWithAlphanumeric()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with AlphaNumeric...',
			'from'		=> 'TEST'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testBulkSMSSending()
	{
		$response = $this->client->sendBulk([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing bulk sending...'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('SMSMessageData', $response_array);
	}

	public function testPremiumSMSWithoutKeyword()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->sendPremium([
				'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
				'linkId'	=> 'messageLinkId',
				'from'		=> '12345',
				'message' 	=> 'Testing SMS without keyword...'
			])
		);
	}

	public function testPremiumSMSWithoutLinkId()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->sendPremium([
				'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
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
				'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
				'linkId'	=> 'messageLinkId',
				'keyword'	=> 'Test',
				'message' 	=> 'Testing...'
			])
		);
	}

	public function testPremiumSMSSending()
	{
		$response = $this->client->sendPremium([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
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
				'phoneNumber' 	=> Fixtures::$phoneNumber,
				'keyword'		=> 'BOOM'
			])
		);
	}

	public function testCreateSubscriptionWithMissingKeyword()
	{
		$this->assertArraySubset(
			['status' => 'error'],
			$response = $this->client->createSubscription([
				'phoneNumber' 	=> Fixtures::$phoneNumber,
				'shortCode'		=> '12345'
			])
		);
	}

	public function testCreateSubscription()
	{
		$response = $this->client->createSubscription([
			'phoneNumber' 	=> Fixtures::$phoneNumber,
			'shortCode'		=> Fixtures::$shortCode,
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
			'phoneNumber' 	=> Fixtures::$phoneNumber, 
			'shortCode'		=> Fixtures::$shortCode,
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
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> 'Test'
		]);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('responses', $response_array);
	}
}