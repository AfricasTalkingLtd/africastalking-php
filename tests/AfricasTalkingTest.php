<?php

namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;

#[\AllowDynamicProperties]
class AfricasTalkingTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->username = Fixtures::$username;
        $this->apiKey = Fixtures::$apiKey;

        $this->client = new AfricasTalking($this->username, $this->apiKey);
    }

    public function test_sms_class()
    {
        $this->assertInstanceOf(\AfricasTalking\SDK\SMS::class, $this->client->sms());
    }

    public function test_content_class()
    {
        $this->assertInstanceOf(\AfricasTalking\SDK\Content::class, $this->client->content());
    }

    public function test_airtime_class()
    {
        $this->assertInstanceOf(\AfricasTalking\SDK\Airtime::class, $this->client->airtime());
    }

    public function test_voice_class()
    {
        $this->assertInstanceOf(\AfricasTalking\SDK\Voice::class, $this->client->voice());
    }

    public function test_application_class()
    {
        $this->assertInstanceOf(\AfricasTalking\SDK\Application::class, $this->client->application());
    }

    public function test_mobile_data_class()
    {
        $this->assertInstanceOf(\AfricasTalking\SDK\MobileData::class, $this->client->mobileData());
    }
}
