<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

#[\AllowDynamicProperties]
class ContentTest extends \PHPUnit\Framework\TestCase
{
	public function setUp(): void
	{
        $this->username = Fixtures::$username;
        $this->apiKey 	= Fixtures::$apiKey;

        $at 			= new AfricasTalking($this->username, $this->apiKey);

        $this->client 	   = $at->content();
        $this->tokenClient = $at->token();
    }

    public function send()
    {
        $response = $this->client->send([
			'to' 		=> Fixtures::$multiplePhoneNumbersSMS, 
			'linkId'	=> 'messageLinkId',
			'keyword'	=> Fixtures::$keyword,
			'from'		=> Fixtures::$shortCode,
			'message' 	=> 'Testing Premium...'
		]);

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
        $this->assertEquals('success',$response['status']);
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
