<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Insights;

use Africastalking\DTO\Response\SimSwapResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class SimSwapRequest extends Request implements HasBody
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
        return new SimSwapResponse(
            responses: $response->json('responses'),
            status: $response->json('status'),
            transactionId: $response->json('transactionId'),
            cost: $response->json('totalCost.amount'),
            currencyCode: $response->json('totalCost.currencyCode'),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/sim-swap';
    }

    /**
     * @return mixed[]
     */
    protected function defaultBody(): array
    {
        return $this->_payload;
    }
}
