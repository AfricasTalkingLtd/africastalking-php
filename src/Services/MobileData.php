<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Response\WalletBalanceResponse;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\MobileData\SendRequest;
use Africastalking\Saloon\MobileData\StatusQueryRequest;
use Africastalking\Saloon\MobileData\WalletBalanceRequest;
use JsonException;

class MobileData extends Service
{
    public function baseUrl(): string
    {
        return $this->credentials->isSandbox()
            ? 'https://bundles.sandbox.africastalking.com'
            : 'https://bundles.africastalking.com';
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return [
            'username' => $this->credentials->username,
            'productName' => $this->credentials->bundlesProduct,
            'recipients' => $this->recipients,
        ];
    }

    /**
     * @return array<mixed, mixed>
     * @throws JsonException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function send(): array
    {
        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            idempotencyKey: $this->idempotencyKey,
            baseUrl: $this->baseUrl(),
        );

        return $connector->send(
            new SendRequest($this->payload()),
        )->json();
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
        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            baseUrl: $this->baseUrl(),
        );

        $request = new StatusQueryRequest($id, $this->credentials->username);

        return $connector->send($request)->json();
    }

    /**
     * @param string|list<array<string,mixed>> $phoneNumbers
     * @param int|float $quantity
     * @param string $unit
     * @param string $validity
     * @return $this
     */
    public function to(string|array $phoneNumbers, int|float $quantity, string $unit, string $validity): static
    {
        if (is_string($phoneNumbers)) {
            $phoneNumbers = [$phoneNumbers];
        }

        foreach ($phoneNumbers as $number) {
            $this->recipients[] = [
                'phoneNumber' => $number,
                'quantity' => $quantity,
                'unit' => $unit,
                'validity' => $validity,
                'metadata' => ['phoneNumber' => $number],
            ];
        }

        return $this;
    }

    public function walletBalance(): WalletBalanceResponse
    {
        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            baseUrl: $this->baseUrl(),
        );

        $request = new WalletBalanceRequest($this->credentials->username);

        return $connector->send($request)->dto();
    }
}
