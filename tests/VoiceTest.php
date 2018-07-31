<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class VoiceTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->voice();
    }

    public function testCall()
    {
		$response = $this->client->call([
			'from' => Fixtures::$voicePhoneNumber,
			'to' => Fixtures::$voicePhoneNumber2
		]);
		$this->assertObjectHasAttribute('entries', $response['data']);
        
    }

    public function testCallsMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->call([
                'from' => Fixtures::$voicePhoneNumber
            ])
		);
	}

    public function testFetchQueuedCalls()
    {
		$response = $this->client->fetchQueuedCalls([
            'phoneNumber' => Fixtures::$voicePhoneNumber,
            'name'        => 'someQueueName'
        ]);

		$this->assertArrayHasKey('status', $response);
    }

    public function testFetchQueuedCallsMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->fetchQueuedCalls()
		);
	}

    public function testUploadMediaFile()
    {
		$response = $this->client->uploadMediaFile([
            'phoneNumber' => Fixtures::$voicePhoneNumber,
			'url' => Fixtures::$mediaUrl
		]);

		$this->assertArrayHasKey('status', $response);
    }

    public function testuploadMediaFileMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->uploadMediaFile([
                'url' => 'test@google'
            ])
		);
	}

    public function testuploadMediaFileCannotBeEmpty()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->uploadMediaFile()
		);
    }
    
    // public function testMessageBuilder()
    // {
    //     // TODO
    // }

}
