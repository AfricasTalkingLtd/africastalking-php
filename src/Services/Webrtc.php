<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Credentials;
use Africastalking\DTO\Response\CapabilityTokenResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\Voice\CapabilityTokenRequest;

class Webrtc extends Service
{
    private string $agentName = '';
    private ?bool $incoming = null;
    private ?bool $outgoing = null;
    private ?string $ttl = null;

    public function baseUrl(): string
    {
        return 'https://webrtc.africastalking.com';
    }

    public function disableIncoming(): static
    {
        $this->incoming = false;

        return $this;
    }

    public function disableOutgoing(): static
    {
        $this->outgoing = false;

        return $this;
    }

    public function enableIncoming(): static
    {
        $this->incoming = true;

        return $this;
    }

    public function enableOutgoing(): static
    {
        $this->outgoing = true;

        return $this;
    }

    public function expire(string $ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function for(string $agentName): static
    {
        $this->agentName = $agentName;

        return $this;
    }

    /**
     * @return array<string,string|int|bool>
     */
    public function payload(): array
    {
        return array_filter(
            array: [
                'username' => $this->credentials->username,
                'clientName' => $this->agentName,
                'phoneNumber' => $this->credentials->voicePhone,
                'incoming' => $this->incoming,
                'outgoing' => $this->outgoing,
                'expire' => $this->ttl,
            ],
            callback: fn($value) => null !== $value,
        );
    }

    public function phoneNumber(string $phone): static
    {
        $this->credentials = new Credentials(
            username: $this->credentials->username,
            apiKey: $this->credentials->apiKey,
            voicePhone: $phone,
        );

        return $this;
    }

    public function token(?string $clientName = null, ?string $phoneNumber = null): CapabilityTokenResponse
    {
        if ($clientName) {
            $this->for($clientName);
        }

        if ($phoneNumber) {
            $this->phoneNumber($phoneNumber);
        }

        if ('' === $this->agentName) {
            throw AfricastalkingException::clientNameMissing();
        }

        if (null === $this->credentials->voicePhone) {
            throw AfricastalkingException::phoneNumberMissing();
        }

        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            baseUrl: $this->baseUrl(),
        );

        $request = new CapabilityTokenRequest($this->payload());

        return $connector->send($request)->dto();
    }
}
