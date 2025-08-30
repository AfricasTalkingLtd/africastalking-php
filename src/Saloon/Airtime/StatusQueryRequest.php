<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Airtime;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\AcceptsJson;

class StatusQueryRequest extends Request
{
    use AcceptsJson;

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $id,
        public readonly string $username,
    ) {}
    public function resolveEndpoint(): string
    {
        return '/query/transaction/find';
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'username' => $this->username,
            'transactionId' => $this->id,
        ];
    }
}
