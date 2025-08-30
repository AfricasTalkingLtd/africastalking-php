<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class ApplicationBalance extends ATResponse
{
    public function __construct(
        public string $currency,
        public float $amount,
    ) {}
}
