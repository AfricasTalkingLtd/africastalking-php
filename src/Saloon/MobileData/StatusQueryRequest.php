<?php

declare(strict_types=1);

namespace Africastalking\Saloon\MobileData;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\AcceptsJson;

class StatusQueryRequest extends Request
{
    use AcceptsJson;

    protected Method $method =  Method::GET;

    public function __construct(
        public readonly string $id,
        public readonly string $username,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/query/transaction/find';
    }

    protected function defaultQuery(): array
    {
        return [
            'username' => $this->username,
            'transactionId' => $this->id,
        ];
    }
}
