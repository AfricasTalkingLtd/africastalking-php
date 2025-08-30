<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class AirtimeResponse extends ATResponse
{
    /**
     * @param string $error
     * @param int $sent
     * @param list<mixed> $responses
     * @param string|null $totalAmount
     * @param string|null $totalDiscount
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        public string $error,
        public int $sent,
        public array $responses,
        public ?string $totalAmount,
        public ?string $totalDiscount,
        public array $rawResponse,
    ) {}
}
