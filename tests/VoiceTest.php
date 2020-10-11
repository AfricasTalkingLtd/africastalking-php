<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

class VoiceTest extends \PHPUnit\Framework\TestCase
{
	public function setUp(): void
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
        $response = $this->client->call([
            'from' => Fixtures::$voicePhoneNumber
        ]);

        $this->assertArrayHasKey('status',$response);
        $this->assertEquals('error',$response['status']);
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
        $response = $this->client->fetchQueuedCalls();

        $this->assertArrayHasKey('status',$response);
        $this->assertEquals('error',$response['status']);
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
        $response = $this->client->uploadMediaFile([
            'url' => 'test@google'
        ]);

        $this->assertArrayHasKey('status',$response);
        $this->assertEquals('error',$response['status']);
	}

    public function testuploadMediaFileCannotBeEmpty()
    {
        $response = $this->client->uploadMediaFile();

        $this->assertArrayHasKey('status',$response);
        $this->assertEquals('error',$response['status']);
    }
    
    // public function testMessageBuilder()
    // {
    //     // TODO
    // }

}
