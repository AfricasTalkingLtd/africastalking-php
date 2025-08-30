<?php

declare(strict_types=1);

namespace Africastalking\Saloon;

use Africastalking\DTO\Credentials;
use Composer\InstalledVersions;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class AfricastalkingConnector extends Connector
{
    use AlwaysThrowOnErrors;

    public function __construct(
        public readonly Credentials $auth,
        ?string                     $idempotencyKey = null,
        private readonly ?string    $baseUrl = null,
    ) {
        if (null !== $idempotencyKey) {
            $this->headers()->add('Idempotency-Key', $idempotencyKey);
        }
    }

    public function resolveBaseUrl(): string
    {
        if ($this->baseUrl) {
            return $this->baseUrl;
        }

        return $this->auth->isSandbox()
            ? 'https://api.sandbox.africastalking.com/version1'
            : 'https://api.africastalking.com/version1';
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator($this->auth->apiKey, 'apiKey');
    }

    protected function defaultHeaders(): array
    {
        return [
            'User-Agent' => $this->userAgent(),
        ];
    }

    protected function userAgent(): string
    {
        return 'php-sdk ' . InstalledVersions::getPrettyVersion(
            'africastalking/sdk',
        );
    }
}
