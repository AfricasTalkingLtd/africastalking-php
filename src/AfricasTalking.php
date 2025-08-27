<?php

namespace AfricasTalking\SDK;

use GuzzleHttp\Client;

class AfricasTalking
{
    public const BASE_DOMAIN = 'africastalking.com';

    public const BASE_SANDBOX_DOMAIN = 'sandbox.' . self::BASE_DOMAIN;

    protected string $username;

    protected string $apiKey;

    protected Client $client;

    protected Client $contentClient;

    protected Client $voiceClient;

    protected Client $tokenClient;

    protected Client $mobileDataClient;

    protected string $baseDomain;

    public string $baseUrl;

    protected string $voiceUrl;

    protected string $checkoutTokenUrl;

    protected string $contentUrl;

    protected string $mobileDataUrl;

    public function __construct(string $username, string $apiKey)
    {
        if ($username === 'sandbox') {
            $this->baseDomain = self::BASE_SANDBOX_DOMAIN;
        } else {
            $this->baseDomain = self::BASE_DOMAIN;
        }

        $this->baseUrl = 'https://api.' . $this->baseDomain . '/version1/';
        $this->voiceUrl = 'https://voice.africastalking.com/';
        $this->mobileDataUrl = 'https://bundles.' . $this->baseDomain . '/';
        $this->contentUrl = ($username === 'sandbox') ? ($this->baseUrl) : ('https://content.' . $this->baseDomain . '/version1/');
        $this->checkoutTokenUrl = 'https://api.' . $this->baseDomain . '/';

        if ($username === 'sandbox') {
            $this->contentUrl = $this->baseUrl;
        }

        $this->username = $username;
        $this->apiKey = $apiKey;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ],
        ]);

        $this->contentClient = new Client([
            'base_uri' => $this->contentUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ],
        ]);

        $this->voiceClient = new Client([
            'base_uri' => $this->voiceUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ],
        ]);

        $this->mobileDataClient = new Client([
            'base_uri' => $this->mobileDataUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        $this->tokenClient = new Client([
            'base_uri' => $this->checkoutTokenUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function sms(): SMS
    {
        $content = new Content($this->contentClient, $this->username, $this->apiKey);

        return new SMS($this->client, $this->username, $this->apiKey, $content);
    }

    public function content(): Content
    {
        return new Content($this->contentClient, $this->username, $this->apiKey);
    }

    public function airtime(): Airtime
    {
        return new Airtime($this->client, $this->username, $this->apiKey);
    }

    public function voice(): Voice
    {
        return new Voice($this->voiceClient, $this->username, $this->apiKey);
    }

    public function application(): Application
    {
        return new Application($this->client, $this->username, $this->apiKey);
    }

    public function mobileData(): MobileData
    {
        return new MobileData($this->mobileDataClient, $this->username, $this->apiKey);
    }

    public function token(): Token
    {
        return new Token($this->tokenClient, $this->username, $this->apiKey);
    }
}
