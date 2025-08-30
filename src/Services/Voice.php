<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Credentials;
use Africastalking\DTO\Response\VoiceCallResponse;
use Africastalking\DTO\Response\VoiceQueueStatus;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\Voice\CallRequest;
use Africastalking\Saloon\Voice\QueueStatusRequest;

class Voice extends Service
{
    /**
     * @var list<array<string, string>>|null
     */
    public ?array $actions = null;

    public function as(string $callerId): static
    {
        $this->credentials = new Credentials(
            username: $this->credentials->username,
            apiKey: $this->credentials->apiKey,
            voicePhone: $callerId,
        );

        return $this;
    }

    /**
     * @param string|list<string>|null $recipients
     * @param string|null $callerId
     * @param list<array<string, string>>|null $actions
     * @return VoiceCallResponse
     * @throws AfricastalkingException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function call(
        null|string|array $recipients = null,
        null|string $callerId = null,
        null|array $actions = null,
    ): VoiceCallResponse {
        $this->set($recipients, $callerId, $actions);
        $this->validate();

        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            baseUrl: $this->baseUrl(),
        );

        $request = new CallRequest($this->payload());

        return $connector->send($request)->dto();
    }

    public function fetchQueuedCalls(null|string $phoneNumber = null): VoiceQueueStatus
    {
        $phoneNumber ??= $this->credentials->voicePhone;

        if (null === $phoneNumber) {
            throw AfricastalkingException::phoneNumberMissing();
        }

        $connector = new AfricastalkingConnector(
            auth: $this->credentials,
            baseUrl: $this->baseUrl(),
        );

        $request = new QueueStatusRequest([
            'phoneNumbers' => $phoneNumber,
            'username' => $this->credentials->username,
        ]);

        return $connector->send($request)->dto();
    }

    /**
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    public function payload(array $options = []): array
    {
        $data = [
            'username' => $this->credentials->username,
            'from' => $this->credentials->voicePhone,
            'to' => $this->recipients,
        ];

        if ($this->actions) {
            $data['voiceActions'] = $this->actions;
        }

        return array_merge(
            $data,
            $options,
        );
    }

    /**
     * @param string|list<string>|null $recipients
     * @param string|null $callerId
     * @param list<array<string, string>>|null $actions
     * @return void
     */
    public function set(array|string|null $recipients, ?string $callerId, ?array $actions): void
    {
        if ($recipients) {
            $this->to($recipients);
        }

        if ($callerId) {
            $this->as($callerId);
        }

        if ($actions) {
            $this->withActions($actions);
        }
    }

    /**
     * @param string|list<string> $phoneNumbers
     * @return $this
     */
    public function to(string|array $phoneNumbers): static
    {
        if (is_string($phoneNumbers)) {
            $phoneNumbers = [$phoneNumbers];
        }

        $this->recipients = array_values(
            array_unique(
                array_merge($this->recipients, $phoneNumbers),
            ),
        );

        return $this;
    }

    /**
     * @param list<array<string, string>> $actions
     * @return $this
     */
    public function withActions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    protected function baseUrl(): string
    {
        return 'https://voice.africastalking.com';
    }

    protected function validate(): void
    {
        if ( ! $this->credentials->voicePhone) {
            throw AfricastalkingException::callerIdMissing();
        }

        if ([] === $this->recipients) {
            throw AfricastalkingException::recipientsMissing();
        }
    }
}
