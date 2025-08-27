<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class SMSTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->sms();
        $this->tokenClient = $at->token();
    }

    public function test_sms_with_empty_message()
    {
        $response = $this->client->send([
            'to' => Fixtures::$multiplePhoneNumbersSMS,
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }

    public function test_sms_with_empty_recipient()
    {
        $response = $this->client->send([
            'message' => 'Testing...',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }

    public function test_single_sms_sending()
    {
        $response = $this->client->send([
            'to' => Fixtures::$phoneNumber,
            'message' => 'Testing SMS...',
        ]);

        $this->assertObjectHasProperty('SMSMessageData', $response['data']);
    }

    public function test_multiple_sms_sending()
    {
        $response = $this->client->send([
            'to' => Fixtures::$multiplePhoneNumbersSMS,
            'message' => 'Testing multiple sending...',
        ]);

        $this->assertObjectHasProperty('SMSMessageData', $response['data']);
    }

    public function test_sms_sending_with_shortcode()
    {
        $response = $this->client->send([
            'to' => Fixtures::$multiplePhoneNumbersSMS,
            'message' => 'Testing with short code...',
            'from' => Fixtures::$shortCode,
        ]);

        $this->assertObjectHasProperty('SMSMessageData', $response['data']);
    }

    public function test_sms_sending_with_alphanumeric()
    {
        $response = $this->client->send([
            'to' => Fixtures::$multiplePhoneNumbersSMS,
            'message' => 'Testing with AlphaNumeric...',
            'from' => Fixtures::$alphanumeric,
        ]);

        $this->assertObjectHasProperty('SMSMessageData', $response['data']);
    }

    public function test_premium_sms_sending()
    {
        $response = $this->client->sendPremium([
            'to' => Fixtures::$multiplePhoneNumbersSMS,
            'linkId' => 'messageLinkId',
            'keyword' => Fixtures::$keyword,
            'from' => Fixtures::$shortCode,
            'message' => 'Testing Premium...',
        ]);

        $this->assertObjectHasProperty('SMSMessageData', $response['data']);
    }

    public function test_fetch_messages()
    {
        $response = $this->client->fetchMessages(['lastReceivedId' => '8796']);

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
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Failed', $response['data']->status);
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
