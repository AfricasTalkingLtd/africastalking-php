<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class VoiceQueueStatus extends ATResponse
{
    /**
     * @param list<array> $entries
     * @param string $errorMessage
     * @param string $status
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        public array $entries,
        public string $errorMessage,
        public string $status,
        public array $rawResponse,
    ) {}
}
