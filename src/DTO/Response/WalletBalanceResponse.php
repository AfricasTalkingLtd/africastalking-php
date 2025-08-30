<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class WalletBalanceResponse extends ATResponse
{
    public function __construct(
        public string $status,
        public string|null $currencyCode,
        public int|float|null $amount,
    ) {}
}
