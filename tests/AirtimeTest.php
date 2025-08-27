<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class AirtimeTest extends \PHPUnit\Framework\TestCase
{
    protected function setup(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->airtime();
    }

    public function test_send_airtime_to_one()
    {
        $response = $this->client->send([
            'recipients' => [[
                'phoneNumber' => Fixtures::$phoneNumber,
                'currencyCode' => Fixtures::$currencyCode,
                'amount' => Fixtures::$amount,
            ]],
        ]);

        $this->assertObjectHasProperty('responses', $response['data']);
    }

    public function test_send_airtime_idempotency()
    {
        $response = $this->client->send([
            'recipients' => [[
                'phoneNumber' => Fixtures::$phoneNumber,
                'currencyCode' => Fixtures::$currencyCode,
                'amount' => Fixtures::$amount,
            ]],
        ], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
        ]);

        $this->assertObjectHasProperty('responses', $response['data']);
    }

    public function test_send_airtime_to_many()
    {
        $response = $this->client->send([
            'recipients' => [[
                'phoneNumber' => Fixtures::$phoneNumber,
                'currencyCode' => Fixtures::$currencyCode,
                'amount' => Fixtures::$amount,
            ], [
                'phoneNumber' => '+2347038151149',
                'currencyCode' => 'NGN',
                'amount' => '10000',
            ]],
        ]);

        $this->assertObjectHasProperty('responses', $response['data']);
    }
}
