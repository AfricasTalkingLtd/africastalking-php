<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Auth;

use Africastalking\DTO\Response\AuthTokenResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class TokenRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $username,
    ) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        return new AuthTokenResponse(
            token: $response->json('token'),
            lifetime: (int) ($response->json('lifetimeInSeconds')),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/auth-token/generate';
    }

    /**
     * @return string[]
     */
    protected function defaultBody(): array
    {
        return ['username' => $this->username];
    }
}
