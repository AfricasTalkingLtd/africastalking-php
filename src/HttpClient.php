<?php

namespace AfricasTalking\SDK;
use GuzzleHttp\Client;

abstract class HttpClient
{
    public function make($contentUrl, $contentType)
    {
        $client = new Client([
            'base_uri' => $contentUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => $contentType,
                'Accept' => 'application/json'
            ]
        ]);

        return $client;
    }
}