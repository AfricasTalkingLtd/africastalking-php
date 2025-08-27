<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class VoiceTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->voice();
    }

    public function test_call()
    {
        $this->markTestSkipped('Not in sandbox');

        $response = $this->client->call([
            'from' => Fixtures::$voicePhoneNumber,
            'to' => Fixtures::$voicePhoneNumber2,
        ]);
        $this->assertObjectHasProperty('entries', $response['data']);

    }

    public function test_calls_must_have_required_attributes()
    {
        $response = $this->client->call([
            'from' => Fixtures::$voicePhoneNumber,
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }

    public function test_fetch_queued_calls()
    {
        $this->markTestSkipped('Not in sandbox');

        $response = $this->client->fetchQueuedCalls([
            'phoneNumber' => Fixtures::$voicePhoneNumber,
            'name' => 'someQueueName',
        ]);

        $this->assertArrayHasKey('status', $response);
    }

    public function test_fetch_queued_calls_must_have_required_attributes()
    {
        $this->markTestSkipped('Not in sandbox');

        $response = $this->client->fetchQueuedCalls();

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }

    public function test_upload_media_file()
    {
        $this->markTestSkipped('Not in sandbox');

        $response = $this->client->uploadMediaFile([
            'phoneNumber' => Fixtures::$voicePhoneNumber,
            'url' => Fixtures::$mediaUrl,
        ]);

        $this->assertArrayHasKey('status', $response);
    }

    public function testupload_media_file_must_have_required_attributes()
    {
        $response = $this->client->uploadMediaFile([
            'url' => 'test@google',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }

    public function testupload_media_file_cannot_be_empty()
    {
        $response = $this->client->uploadMediaFile();

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }

    // public function testMessageBuilder()
    // {
    //     // TODO
    // }

}
