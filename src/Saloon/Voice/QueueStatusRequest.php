<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Voice;

use Africastalking\DTO\Response\VoiceQueueStatus;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Plugins\AcceptsJson;

class QueueStatusRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasFormBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed> $_payload
     */
    public function __construct(private readonly array $_payload) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        return new VoiceQueueStatus(
            entries: $response->json('entries'),
            errorMessage: $response->json('errorMessage'),
            status: $response->json('status'),
            rawResponse: $response->json(),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/queueStatus';
    }

    /**
     * @return mixed[]
     */
    protected function defaultBody(): array
    {
        return $this->_payload;
    }
}
