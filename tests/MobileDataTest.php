<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use AfricasTalking\SDK\MobileData;
use GuzzleHttp\Exception\GuzzleException;

class MobileDataTest extends \PHPUnit\Framework\TestCase
{
	public function setUp(): void
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->mobileData();
    }

    
	public function testSend()
	{
        $response = $this->client->send([
            'productName' => Fixtures::$productName,
            'recipients'  => Fixtures::$MobileDataRecipients,
		]);
        $this->assertArrayHasKey('status', $response);
	}

    public function testFindTransaction()
    {
        $response = $this->client->findTransaction([
            'transactionId' => Fixtures::$transactionId
        ]);
        $this->assertEquals('Failure', $response['data']->status);
    }

    public function testFetchWalletBalance()
    {
        $response = $this->client->fetchWalletBalance();
        $this->assertEquals('Success', $response['data']->status);
    }
}
