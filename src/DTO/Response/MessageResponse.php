<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class MessageResponse extends ATResponse
{
    /**
     * @param string $message
     * @param array<string, mixed> $recipients
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        public string $message,
        public array $recipients,
        public array $rawResponse,
    ) {}
}
