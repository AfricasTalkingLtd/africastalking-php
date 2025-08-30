<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Airtime;

use Africastalking\DTO\Response\AirtimeResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class SendRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed> $_payload
     */
    public function __construct(
        private readonly array       $_payload,
    ) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        return new AirtimeResponse(
            error: $response->json('errorMessage'),
            sent: $response->json('numSent'),
            responses: $response->json('responses'),
            totalAmount: $response->json('totalAmount'),
            totalDiscount: $response->json('totalDiscount'),
            rawResponse: $response->json(),
        );
    }
    public function resolveEndpoint(): string
    {
        return '/airtime/send';
    }

    /**
     * @return mixed[]
     */
    protected function defaultBody(): array
    {
        return $this->_payload;
    }
}
