<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\AuthTokenResponse;
use Africastalking\Saloon\Auth\TokenRequest;
use Africastalking\Services\AuthToken;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be constructed', function (): void {
    Config::preventStrayRequests();

    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->token();

    expect($subject)->toBeInstanceOf(AuthToken::class);
});

it('uses the correct baseUrl', function (string $username, string $baseUrl): void {
    $subject = Africastalking::make(
        $username,
        $_ENV['API_KEY'],
    )->token();

    expect($subject)
        ->baseUrl()->toBe($baseUrl);
})->with([
    ['sandbox', 'https://api.sandbox.africastalking.com'],
    ['cool-app', 'https://api.africastalking.com'],
]);

it('can create a token for the sandbox environment', function (): void {
    MockClient::global([
        TokenRequest::class => MockResponse::fixture('auth/sandbox-token-request'),
    ]);

    $response = Africastalking::make('sandbox', $_ENV['API_KEY'])
        ->token()
        ->generate();

    expect($response)
        ->toBeInstanceOf(AuthTokenResponse::class)
        ->lifetime->toBe(3600)
        ->token->toBeString()
        ->token->not->toBe('')
        ->token->toHaveLength(70);
});

it('can create a token', function (): void {
    MockClient::global([
        TokenRequest::class => MockResponse::fixture('auth/token-request'),
    ]);

    $response = Africastalking::make('ubwedede', $_ENV['API_KEY'])
        ->token()
        ->generate();

    expect($response)
        ->toBeInstanceOf(AuthTokenResponse::class)
        ->lifetime->toBe(3600)
        ->token->toBeString()
        ->token->not->toBe('')
        ->token->toHaveLength(70);
});
