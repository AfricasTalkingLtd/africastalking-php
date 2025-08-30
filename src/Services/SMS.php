<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Credentials;
use Africastalking\DTO\Response\MessageResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\BulkSms\SandboxRequest;
use Africastalking\Saloon\BulkSms\SendRequest;

class SMS extends Service
{
    public ?string $maskedPhoneNumber = null;
    public ?string $message = null;

    public function as(string $senderId): static
    {
        if ('' === $senderId) {
            $senderId = null;
        }

        $this->credentials = new Credentials(
            username: $this->credentials->username,
            apiKey: $this->credentials->apiKey,
            senderId: $senderId,
            voicePhone: $this->credentials->voicePhone,
            bundlesProduct: $this->credentials->bundlesProduct,
        );

        return $this;
    }

    public function hashed(string $phoneNumberHash): static
    {
        if (65 !== mb_strlen($phoneNumberHash)) {
            throw AfricastalkingException::invalidHashedNumber();
        }

        $this->maskedPhoneNumber = $phoneNumberHash;

        return $this;
    }

    public function message(string $text): static
    {
        $this->message = $text;

        return $this;
    }

    /**
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws AfricastalkingException
     */
    public function payload(array $options = []): array
    {
        $data = array_merge([
            'senderId' => $this->credentials->senderId,
            'phoneNumbers' => $this->recipients,
            'message' => $this->message,
            'maskedNumber' => $this->maskedPhoneNumber,
            'username' => $this->credentials->username,
        ], $options);

        if (null === $data['message']) {
            throw AfricastalkingException::messageEmpty();
        }

        if (null === $data['maskedNumber'] && [] === $data['phoneNumbers']) {
            throw AfricastalkingException::recipientsMissing();
        }

        if (null === $data['maskedNumber']) {
            unset($data['maskedNumber']);
        }

        return $data;
    }

    /**
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws AfricastalkingException
     */
    public function sandboxPayload(array $options = []): array
    {
        $data = array_merge([
            'from' => $this->credentials->senderId,
            'to' => implode(',', $this->recipients),
            'message' => $this->message,
            'username' => $this->credentials->username,
        ], $options);

        if (null === $data['message']) {
            throw AfricastalkingException::messageEmpty();
        }

        if ('' === $data['to']) {
            throw AfricastalkingException::recipientsMissing();
        }

        return $data;
    }

    /**
     * @param array<string,mixed> $options
     * @return MessageResponse
     * @throws AfricastalkingException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function send(array $options = []): MessageResponse
    {
        $connector = new AfricastalkingConnector(
            $this->credentials,
            $this->idempotencyKey,
        );

        $request = $this->credentials->isSandbox()
            ? new SandboxRequest($this->sandboxPayload($options))
            : new SendRequest($this->payload($options));

        return $connector->send($request)->dtoOrFail();
    }

    /**
     * @param string|list<string> $recipients
     * @return $this
     */
    public function to(string|array $recipients): static
    {
        if (is_string($recipients)) {
            $recipients = [$recipients];
        }

        $this->recipients = array_unique([...$this->recipients, ...$recipients]);

        return $this;
    }
}
