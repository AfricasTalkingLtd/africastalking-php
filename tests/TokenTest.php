<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
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

		$this->assertEquals('Success', $response['data']->description);
    }

    
}