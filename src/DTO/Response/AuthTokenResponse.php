<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class AuthTokenResponse extends ATResponse
{
    public function __construct(
        public string $token,
        public int $lifetime,
    ) {}
}
