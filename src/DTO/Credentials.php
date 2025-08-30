<?php

declare(strict_types=1);

namespace Africastalking\DTO;

use SensitiveParameter;

readonly class Credentials
{
    public function __construct(
        #[SensitiveParameter]
        public string  $username,
        #[SensitiveParameter]
        public string  $apiKey,
        public ?string $senderId = null,
        public ?string $voicePhone = null,
        public ?string $bundlesProduct = null,
    ) {}

    public function isLive(): bool
    {
        return ! $this->isSandbox();
    }

    public function isSandbox(): bool
    {
        return 'sandbox' === mb_strtolower($this->username);
    }
}
