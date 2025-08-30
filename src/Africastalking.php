<?php

declare(strict_types=1);

namespace Africastalking;

use Africastalking\DTO\Credentials;
use Africastalking\Services\Airtime;
use Africastalking\Services\Application;
use Africastalking\Services\AuthToken;
use Africastalking\Services\Insights;
use Africastalking\Services\MobileData;
use Africastalking\Services\SMS;
use Africastalking\Services\Voice;
use Africastalking\Services\Webrtc;
use SensitiveParameter;

readonly class Africastalking
{
    public Credentials $credentials;

    final public function __construct(
        #[SensitiveParameter]
        public string  $username,
        #[SensitiveParameter]
        public string  $apiKey,
        public ?string $productName = null,
    ) {
        $this->credentials = new Credentials(
            username: $username,
            apiKey: $apiKey,
            bundlesProduct: $this->productName,
        );
    }

    public static function make(
        string  $username,
        string  $apiKey,
        ?string $productName = null,
    ): static {
        return new static($username, $apiKey, $productName);
    }

    public function airtime(): Airtime
    {
        return new Airtime($this->credentials);
    }

    public function application(): Application
    {
        return new Application($this->credentials);
    }

    public function content(): void {}

    public function insights(): Insights
    {
        return new Insights($this->credentials);
    }

    public function mobileData(): MobileData
    {
        return new MobileData($this->credentials);
    }

    public function sms(): SMS
    {
        return new SMS($this->credentials);
    }

    public function token(): AuthToken
    {
        return new AuthToken($this->credentials);
    }

    public function voice(): Voice
    {
        return new Voice($this->credentials);
    }

    public function webrtc(): Webrtc
    {
        return new Webrtc($this->credentials);
    }
}
