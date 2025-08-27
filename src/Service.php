<?php

namespace AfricasTalking\SDK;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class Service
{
    public function __construct(
        protected Client $client,
        protected string $username,
        protected string $apiKey,
    ) {}

    protected static function error(string $data): array
    {
        return [
            'status' => 'error',
            'data' => $data,
        ];
    }

    protected static function success(ResponseInterface $data): array
    {
        return [
            'status' => 'success',
            'data' => json_decode($data->getBody()->getContents()),
        ];
    }
}
