<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
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

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testMultipleSMSSending()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing multiple sending...'
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testSMSSendingWithShortcode()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with short code...',
			'from'		=> '12345'
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testSMSSendingWithAlphanumeric()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with AlphaNumeric...',
			'from'		=> 'TEST'
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testBulkSMSSending()
	{
		$response = $this->client->sendBulk([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing bulk sending...'
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
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

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testFetchSMS()
	{
		$response = $this->client->fetchMessages();

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testFetchSMSWithLastReceived()
	{
		$response = $this->client->fetchMessages(['lastReceivedId' => '8796']);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
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

		$this->assertArraySubset(
			['status' => 'success'],
			$response
		);
	}

	public function testDeleteSubscription()
	{
		$response = $this->client->deleteSubscription([
			'phoneNumber' 	=> Fixtures::$phoneNumber, 
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> 'Test'
		]);

		$this->assertArraySubset(
			['status' => 'success'],
			$response
		);
	}

	public function testFetchSubscriptions()
	{
		$response = $this->client->fetchSubscriptions([
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> 'Test'
		]);

		$this->assertObjectHasAttribute('responses', $response['data']);
	}
}