<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\ApplicationBalance;
use Africastalking\Saloon\Application\BalanceRequest;
use Africastalking\Services\Application;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->application();

    expect($subject)->toBeInstanceOf(Application::class);
});

it('fetches user balance', function (): void {
    MockClient::global([
        BalanceRequest::class => MockResponse::fixture('application/balance'),
    ]);

    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->application()->balance();

    expect($subject)
        ->toBeInstanceOf(ApplicationBalance::class)
        ->amount->toBe(420.0042)
        ->currency->toBe('USD');
});

it('fetches user balance using an alias', function (): void {
    MockClient::global([
        BalanceRequest::class => MockResponse::fixture('application/balance'),
    ]);

    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->application()->bal();

    expect($subject)
        ->toBeInstanceOf(ApplicationBalance::class)
        ->amount->toBe(420.0042)
        ->currency->toBe('USD');
});
