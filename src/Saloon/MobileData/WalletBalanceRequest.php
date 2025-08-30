<?php

declare(strict_types=1);

namespace Africastalking\Saloon\MobileData;

use Africastalking\DTO\Response\WalletBalanceResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;

class WalletBalanceRequest extends Request
{
    use AcceptsJson;

    protected Method $method = Method::GET;

    public function __construct(public readonly string $username) {}

    public function createDtoFromResponse(Response $response): mixed
    {
        $amount = null;
        $currencyCode = null;

        if ($balance = $response->json('balance')) {
            [$currencyCode, $amount] = explode(' ', $balance);
        }

        return new WalletBalanceResponse(
            status: $response->json('status'),
            currencyCode: $currencyCode,
            amount: (float) $amount,
        );
    }

    public function resolveEndpoint(): string
    {
        return 'query/wallet/balance';
    }

    /**
     * @return string[]
     */
    protected function defaultQuery(): array
    {
        return ['username' => $this->username];
    }
}
