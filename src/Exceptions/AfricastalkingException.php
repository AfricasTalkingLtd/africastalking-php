<?php

declare(strict_types=1);

namespace Africastalking\Exceptions;

use Exception;

final class AfricastalkingException extends Exception
{
    public static function callerIdMissing(): static
    {
        return new static('You must specify the CallerId');
    }

    public static function clientNameMissing(): static
    {
        return new static('You must specify a Client Name to generate a capability token');
    }
    public static function invalidHashedNumber(): static
    {
        return new static('The hashed number must be 64 characters preceded with a +');
    }
    public static function messageEmpty(): static
    {
        return new static('The message cannot be empty');
    }

    public static function phoneNumberMissing(): static
    {
        return new static('The phone number cannot be empty');
    }

    public static function recipientsMissing(): static
    {
        return new static('You must specify at least one recipient');
    }
}
