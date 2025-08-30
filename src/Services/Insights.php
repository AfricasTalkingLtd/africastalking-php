<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Response\SimSwapResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\Insights\SimSwapRequest;

class Insights extends Service
{
    /**
     * @var array<int,string>
     */
    public array $phoneNumbers = [];

    public function baseUrl(): string
    {
        return $this->credentials->isSandbox()
            ? 'https://insights.sandbox.africastalking.com/v1'
            : 'https://insights.africastalking.com/v1';
    }

    /**
     * @param string|array<int,string> $phoneNumbers
     * @return $this
     */
    public function for(string|array $phoneNumbers): static
    {
        return $this->phoneNumbers($phoneNumbers);
    }

    /**
     * @return array<string, string|array<int,string>>
     * @throws AfricastalkingException
     */
    public function payload(): array
    {
        if ([] === $this->phoneNumbers) {
            throw AfricastalkingException::recipientsMissing();
        }

        return [
            'username' => $this->credentials->username,
            'phoneNumbers' =>  $this->phoneNumbers,
        ];
    }

    /**
     * @param string|array<int,string> $phoneNumbers
     * @return $this
     */
    public function phoneNumbers(string|array $phoneNumbers): static
    {
        if (is_string($phoneNumbers)) {
            $phoneNumbers = [$phoneNumbers];
        }

        $this->phoneNumbers = array_unique(
            array_merge($this->phoneNumbers, $phoneNumbers),
        );

        return $this;
    }

    public function send(): SimSwapResponse
    {
        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            idempotencyKey: $this->idempotencyKey,
            baseUrl: $this->baseUrl(),
        );

        $request = new SimSwapRequest($this->payload());

        return $connector->send($request)->dtoOrFail();
    }
}
