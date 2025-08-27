<?php

namespace AfricasTalking\SDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SMS extends Service
{
    public function __construct(
        protected Client $client,
        protected string $username,
        protected string $apiKey,
        protected Content $content,
    ) {
        parent::__construct($client, $username, $apiKey);
    }

    /**
     * @throws GuzzleException
     */
    public function send(array $options): array
    {
        if (empty($options['to']) || empty($options['message'])) {
            return $this->error('recipient and message must be defined');
        }

        if (! is_array($options['to'])) {
            $options['to'] = [$options['to']];
        }

        $data = [
            'username' => $this->username,
            'to' => implode(',', $options['to']),
            'message' => $options['message'],
        ];

        if (array_key_exists('enqueue', $options) && $options['enqueue']) {
            $data['enqueue'] = 1;
        }

        if (! empty($options['from'])) {
            $data['from'] = $options['from'];
        }

        $response = $this->client->post('messaging', ['form_params' => $data]);

        return $this->success($response);
    }

    /**
     * @throws GuzzleException
     */
    public function fetchMessages(array $options = []): array
    {
        if (empty($options['lastReceivedId'])) {
            $options['lastReceivedId'] = 0;
        }

        if (! is_numeric($options['lastReceivedId'])) {
            return $this->error('lastReceivedId must be an integer');
        }

        $data = [
            'username' => $this->username,
            'lastReceivedId' => $options['lastReceivedId'],
        ];

        $response = $this->client->get('messaging', ['query' => $data]);

        return $this->success($response);
    }

    public function sendPremium(array $options): array
    {
        return $this->content->send($options);
    }

    public function createSubscription(array $options): array
    {
        return $this->content->createSubscription($options);
    }

    public function deleteSubscription(array $options): array
    {
        return $this->content->deleteSubscription($options);
    }

    public function fetchSubscriptions(array $options): array
    {
        return $this->content->fetchSubscriptions($options);
    }
}
