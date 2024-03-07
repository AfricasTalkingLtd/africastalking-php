<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

#[\AllowDynamicProperties]
class TokenTest extends \PHPUnit\Framework\TestCase
{
	public function setUp(): void
	{
			$this->username = Fixtures::$username;
			$this->apiKey 	= Fixtures::$apiKey;

			$at 			= new AfricasTalking($this->username, $this->apiKey);

			$this->client 	= $at->token();		
    }

    public function testGenerateAuthToken()
    {
			$response = $this->client->generateAuthToken();
			$this->assertEquals(3600, $response['data']->lifetimeInSeconds);
    }
}
