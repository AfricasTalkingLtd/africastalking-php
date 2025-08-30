<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Credentials;
use Africastalking\Traits\HasIdempotency;

abstract class Service
{
    use HasIdempotency;

    /**
     * @var array<int,mixed>
     */
    public array $recipients = [];
    public function __construct(
        protected Credentials $credentials,
    ) {}
}
