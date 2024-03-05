<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

#[\AllowDynamicProperties]
class SMSTest extends \PHPUnit\Framework\TestCase
{
	public function setUp(): void
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	   = $at->sms();
        $this->tokenClient = $at->token();
	}

	public function testSMSWithEmptyMessage()
	{
        $response = $this->client->send([
            'to' 		=> Fixtures::$multiplePhoneNumbersSMS,
        ]);

        $this->assertArrayHasKey('status',$response);
        $this->assertEquals('error',$response['status']);
	}

	public function testSMSWithEmptyRecipient()
	{
        $response = $this->client->send([
            'message' 	=> 'Testing...'
        ]);

        $this->assertArrayHasKey('status',$response);
        $this->assertEquals('error',$response['status']);
	}

	public function testSingleSMSSending()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$phoneNumber, 
			'message' 	=> 'Testing SMS...'
		]);

		$this->assertObjectHasProperty('SMSMessageData', $response['data']);
	}

	public function testMultipleSMSSending()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing multiple sending...'
		]);

		$this->assertObjectHasProperty('SMSMessageData', $response['data']);
	}

	public function testSMSSendingWithShortcode()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with short code...',
			'from'		=> Fixtures::$shortCode
		]);

		$this->assertObjectHasProperty('SMSMessageData', $response['data']);
	}

	public function testSMSSendingWithAlphanumeric()
	{
		$response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'message' 	=> 'Testing with AlphaNumeric...',
			'from'		=> Fixtures::$alphanumeric
		]);

		$this->assertObjectHasProperty('SMSMessageData', $response['data']);
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

		$this->assertObjectHasProperty('SMSMessageData', $response['data']);
	}

	public function testFetchMessages()
	{
		$response = $this->client->fetchMessages(['lastReceivedId' => '8796']);

		$this->assertObjectHasProperty('SMSMessageData', $response['data']);
	}

	public function testCreateSubscription()
	{
		$response = $this->client->createSubscription([
			'phoneNumber' 	=> Fixtures::$phoneNumber,
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> Fixtures::$keyword,
		]);

        $this->assertArrayHasKey('status',$response);
        $this->assertArrayHasKey('data',$response);
        $this->assertEquals('success',$response['status']);
        $this->assertEquals('Success',$response['data']->status);
	}

	public function testDeleteSubscription()
	{
		$response = $this->client->deleteSubscription([
			'phoneNumber' 	=> Fixtures::$phoneNumber, 
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> Fixtures::$keyword
		]);

		$this->assertArrayHasKey('status',$response);
		$this->assertEquals('success',$response['status']);
	}

	public function testFetchSubscriptions()
	{
		$response = $this->client->fetchSubscriptions([
			'shortCode'		=> Fixtures::$shortCode,
			'keyword'		=> Fixtures::$keyword
		]);

		$this->assertObjectHasProperty('responses', $response['data']);
	}
}
