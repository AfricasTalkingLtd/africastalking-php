<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class SimSwapResponse extends ATResponse
{
    /**
     * @param list<array<string, mixed>> $responses
     * @param string $status
     * @param string $transactionId
     * @param int|float|null $cost
     * @param string|null $currencyCode
     */
    public function __construct(
        public array $responses,
        public string $status,
        public string $transactionId,
        public int|float|null $cost = null,
        public string|null $currencyCode = null,
    ) {}
}
