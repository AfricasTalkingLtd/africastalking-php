<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Voice;

use Africastalking\DTO\Response\CapabilityTokenResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class CapabilityTokenRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed> $_payload
     */
    public function __construct(private readonly array $_payload) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        return new CapabilityTokenResponse(
            token: $response->json('token'),
            clientName: $response->json('clientName'),
            lifetime: (int) $response->json('lifeTimeSec'),
            incoming: $response->json('incoming'),
            outgoing: $response->json('outgoing'),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/capability-token/request';
    }

    /**
     * @return mixed[]
     */
    protected function defaultBody(): array
    {
        return $this->_payload;
    }
}
