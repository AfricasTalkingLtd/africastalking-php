<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class MobileDataTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->mobileData();
    }

    public function test_send()
    {
        $response = $this->client->send([
            'productName' => Fixtures::$productName,
            'recipients' => Fixtures::$MobileDataRecipients,
        ]);
        $this->assertArrayHasKey('status', $response);
    }

    public function test_find_transaction()
    {
        $response = $this->client->findTransaction([
            'transactionId' => Fixtures::$transactionId,
        ]);

        $this->assertObjectHasProperty('status', $response['data']);

        $this->assertTrue(
            in_array($response['data']->status, ['Success', 'Failure']),
        );
    }

    public function test_fetch_wallet_balance()
    {
        $response = $this->client->fetchWalletBalance();

        $this->assertObjectHasProperty('status', $response['data']);

        $this->assertTrue(
            in_array($response['data']->status, ['Success', 'NotAvailable']),
        );
    }
}
