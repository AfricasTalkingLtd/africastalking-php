<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class ContentTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
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
