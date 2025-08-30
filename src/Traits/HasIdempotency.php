<?php

declare(strict_types=1);

namespace Africastalking\Traits;

trait HasIdempotency
{
    public ?string $idempotencyKey = null;

    public function idempotent(string $key): static
    {
        $this->idempotencyKey = $key;

        return $this;
    }

    public function isIdempotent(): bool
    {
        return null !== $this->idempotencyKey;
    }
}
