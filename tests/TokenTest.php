<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class TokenTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->token();		
    }
    
    public function testCreateCheckoutToken()
    {
		$response = $this->client->createCheckoutToken(Fixtures::$phoneNumber);

        $response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertEquals('Success', $response_array['description']);
    }

    
}