<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class CapabilityTokenResponse extends ATResponse
{
    public function __construct(
        public string $token,
        public string $clientName,
        public int $lifetime,
        public bool $incoming,
        public bool $outgoing,
    ) {}
}
