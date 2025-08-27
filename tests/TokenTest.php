<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class TokenTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $at = new AfricasTalking($this->username, $this->apiKey);

        $this->client = $at->token();
    }

    public function test_generate_auth_token()
    {
        $response = $this->client->generateAuthToken();
        $this->assertEquals(3600, $response['data']->lifetimeInSeconds);
    }
}
