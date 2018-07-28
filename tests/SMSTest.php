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

		$this->client 	   = $at->sms();
        $this->tokenClient = $at->token();
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
			'to' 		=> Fixtures::$phoneNumber, 
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
			'from'		=> Fixtures::$shortCode
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testSMSSendingWithAlphanumeric()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with AlphaNumeric...',
			'from'		=> Fixtures::$alphanumeric
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testPremiumSMSSending()
	{
		$response = $this->client->sendPremium([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'linkId'	=> 'messageLinkId',
			'keyword'	=> Fixtures::$keyword,
			'from'		=> Fixtures::$shortCode,
			'message' 	=> 'Testing Premium...'
		]);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testFetchMessages()
	{
		$response = $this->client->fetchMessages(['lastReceivedId' => '8796']);

		$this->assertObjectHasAttribute('SMSMessageData', $response['data']);
	}

	public function testCreateSubscription()
	{
        $checkoutTokenResponse = $this->tokenClient->createCheckoutToken([
            'phoneNumber' => Fixtures::$phoneNumber
        ]);
		$response = $this->client->createSubscription([
			'phoneNumber' 	=> Fixtures::$phoneNumber,
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> Fixtures::$keyword,
            'checkoutToken' => $checkoutTokenResponse['data']->token
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
			'keyword'		=> Fixtures::$keyword
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
			'keyword'		=> Fixtures::$keyword
		]);

		$this->assertObjectHasAttribute('responses', $response['data']);
	}
}
