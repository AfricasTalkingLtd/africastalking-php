<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class AccountTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->account();		
    }
    
    public function testFetchAccount()
    {
		$response = $this->client->fetchAccount();
		$this->assertObjectHasAttribute('UserData', $response['data']);
    }
}