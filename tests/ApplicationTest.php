<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->application();		
	}
    
	public function testFetchAplication()
	{
		$response = $this->client->fetchApplicationData();
		$this->assertObjectHasAttribute('UserData', $response['data']);
	}
}
