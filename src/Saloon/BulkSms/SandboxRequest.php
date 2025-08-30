<?php

declare(strict_types=1);

namespace Africastalking\Saloon\BulkSms;

use Africastalking\DTO\Response\MessageResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Plugins\AcceptsJson;

class SandboxRequest extends Request implements HasBody
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
        return new MessageResponse(
            message: $response->json('SMSMessageData.Message'),
            recipients: $response->json('SMSMessageData.Recipients'),
            rawResponse: $response->json(),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/messaging';
    }

    /**
     * @return mixed[]
     */
    protected function defaultBody(): array
    {
        return $this->_payload;
    }
}
