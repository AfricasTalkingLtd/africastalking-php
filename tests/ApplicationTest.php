<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->application();
    }

    public function test_fetch_aplication()
    {
        $response = $this->client->fetchApplicationData();
        $this->assertObjectHasProperty('UserData', $response['data']);
    }
}
