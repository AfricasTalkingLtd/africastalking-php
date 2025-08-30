<?php

declare(strict_types=1);

namespace Africastalking\Saloon\Application;

use Africastalking\DTO\Response\ApplicationBalance;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;

class BalanceRequest extends Request
{
    use AcceptsJson;

    protected Method $method = Method::GET;

    public function __construct(private readonly string $username) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        $balance = explode(' ', $response->json('UserData.balance'));

        return new ApplicationBalance(
            currency: $balance[0],
            amount: (float) ($balance[1]),
        );
    }

    public function resolveEndpoint(): string
    {
        return '/user';
    }

    protected function defaultQuery(): array
    {
        return ['username' => $this->username];
    }
}
