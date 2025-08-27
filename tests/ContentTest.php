<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class ContentTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->content();
        $this->tokenClient = $at->token();
    }

    public function send()
    {
        $response = $this->client->send([
            'to' => Fixtures::$multiplePhoneNumbersSMS,
            'linkId' => 'messageLinkId',
            'keyword' => Fixtures::$keyword,
            'from' => Fixtures::$shortCode,
            'message' => 'Testing Premium...',
        ]);

        $this->assertObjectHasProperty('SMSMessageData', $response['data']);
    }

    public function test_create_subscription()
    {
        $response = $this->client->createSubscription([
            'phoneNumber' => Fixtures::$phoneNumber,
            'shortCode' => Fixtures::$shortCode,
            'keyword' => Fixtures::$keyword,
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function test_delete_subscription()
    {
        $response = $this->client->deleteSubscription([
            'phoneNumber' => Fixtures::$phoneNumber,
            'shortCode' => Fixtures::$shortCode,
            'keyword' => Fixtures::$keyword,
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function test_fetch_subscriptions()
    {
        $response = $this->client->fetchSubscriptions([
            'shortCode' => Fixtures::$shortCode,
            'keyword' => Fixtures::$keyword,
        ]);

        $this->assertObjectHasProperty('responses', $response['data']);
    }
}
