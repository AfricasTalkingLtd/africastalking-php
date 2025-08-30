<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Response\AuthTokenResponse;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\Auth\TokenRequest;

class AuthToken extends Service
{
    public function baseUrl(): string
    {
        return $this->credentials->isSandbox()
            ? 'https://api.sandbox.africastalking.com'
            : 'https://api.africastalking.com';
    }
    public function generate(): AuthTokenResponse
    {
        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            baseUrl: $this->baseUrl(),
        );

        $request = new TokenRequest($this->credentials->username);

        return $connector->send($request)->dto();
    }
}
