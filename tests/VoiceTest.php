<?php
namespace AfricasTalkingTest;

use AfricasTalking\AfricasTalking;
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

		
		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('entries', $response_array);
        
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
		$response = $this->client->fetchQueuedCalls(Fixtures::$voicePhoneNumber);

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('status', $response_array);
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

		$response_array = json_decode($response['data']->getBody()->getContents(), true);

		$this->assertArrayHasKey('status', $response_array);
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