<?php

declare(strict_types=1);

namespace Africastalking\DTO\Response;

readonly class VoiceCallResponse extends ATResponse
{
    /**
     * @param array<string, mixed> $entries
     * @param string $error
     * @param int $queueSize
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        public array $entries,
        public string $error,
        public int $queueSize,
        public array $rawResponse,
    ) {}
}
