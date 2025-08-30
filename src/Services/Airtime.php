<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Response\AirtimeResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\Airtime\SendRequest;
use Africastalking\Saloon\Airtime\StatusQueryRequest;
use JsonException;
use Throwable;

class Airtime extends Service
{
    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws AfricastalkingException
     */
    public function payload(array $options = []): array
    {
        $data = array_merge([
            'recipients' => $this->recipients,
            'username' => $this->credentials->username,
        ], $options);

        if ([] === $data['recipients']) {
            throw AfricastalkingException::recipientsMissing();
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $options
     * @return AirtimeResponse
     * @throws AfricastalkingException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @throws Throwable
     */
    public function send(array $options = []): AirtimeResponse
    {
        $connector = new AfricastalkingConnector(
            $this->credentials,
            $this->idempotencyKey,
        );

        $request = new SendRequest(
            $this->payload($options),
        );

        return $connector->send($request)->dtoOrFail();
    }

    /**
     * @param string $id
     * @return array<string, string>
     * @throws JsonException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function status(string $id): array
    {
        $connector = new AfricastalkingConnector($this->credentials);

        $request = new StatusQueryRequest($id, $this->credentials->username);

        return $connector->send($request)->json();
    }

    public function to(string $phone, string $currencyCode, int|float $amount): static
    {
        $this->recipients[] = [
            'phoneNumber' => $phone,
            'amount' => "{$currencyCode} {$amount}",
        ];

        return $this;
    }
}
