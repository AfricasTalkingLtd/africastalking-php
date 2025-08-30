<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Voice;

use Africastalking\DTO\Response\VoiceCallResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class CallRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    protected Method $method =  Method::POST;

    /**
     * @param array<string, mixed> $_payload
     */
    public function __construct(private readonly array $_payload) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        return new VoiceCallResponse(
            entries: $response->json('entries'),
            error: $response->json('errorMessage'),
            queueSize: (int) $response->header('X-Current-Queue-Size'),
            rawResponse: $response->json(),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/call';
    }

    /**
     * @return mixed[]
     */
    protected function defaultBody(): array
    {
        return $this->_payload;
    }
}
