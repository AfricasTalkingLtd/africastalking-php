<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\AirtimeResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\Airtime\SendRequest;
use Africastalking\Saloon\Airtime\StatusQueryRequest;
use Africastalking\Services\Airtime;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->airtime();

    expect($subject)->toBeInstanceOf(Airtime::class);
});

it('constructs the payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->to('+254711082000', 'KES', 110)
        ->payload();

    expect($request)->toBe([
        'recipients' => [
            ['phoneNumber' => '+254711082000', 'amount' => 'KES 110'],
        ],
        'username' => $_ENV['USERNAME'],
    ]);
});

it('can set multiple recipients', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->to('+254711082000', 'KES', 110)
        ->to('+254700082000', 'KES', 110);

    expect($subject)
        ->recipients->toBe([
            ['phoneNumber' => '+254711082000', 'amount' => 'KES 110'],
            ['phoneNumber' => '+254700082000', 'amount' => 'KES 110'],
        ])
        ->payload()->toBe([
            'recipients' => [
                ['phoneNumber' => '+254711082000', 'amount' => 'KES 110'],
                ['phoneNumber' => '+254700082000', 'amount' => 'KES 110'],
            ],
            'username' => $_ENV['USERNAME'],
        ]);
});

it('sets the idempotency key', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->idempotent('39f9486a-4517-48c6-be9c-f4dd504deb69');

    expect($subject)
        ->idempotencyKey->toBe('39f9486a-4517-48c6-be9c-f4dd504deb69')
        ->isIdempotent()->toBeTrue();
});

it('sends airtime to a single recipient', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('airtime/single'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->idempotent('39f9486a-4517-48c6-be9c-f4dd504deb69' . time())
        ->to('+254711082000', 'KES', 100)
        ->send();

    expect($response)
        ->toBeInstanceOf(AirtimeResponse::class)
        ->sent->toBe(1)
        ->error->toBe('None')
        ->responses->toBeArray()->toHaveCount(1)
        ->totalAmount->toBe('KES 96.0000')
        ->totalDiscount->toBe('KES 4.0000');
});

it('Handles errors', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('airtime/trial-limit'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->to('+254711082000', 'KES', 2_000)
        ->send();

    expect($response)
        ->toBeInstanceOf(AirtimeResponse::class)
        ->sent->toBe(0)
        ->error->toBe('Request is over the trial limit. Submit your KYC to remove limit')
        ->responses->toBeArray()->toHaveCount(0)
        ->totalAmount->toBe('0')
        ->totalDiscount->toBe('0');
});

it('merges payload in send()', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('airtime/merged-payload'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->send([
            'recipients' => [
                ['phoneNumber' => '+254711082000', 'amount' => 'KES 110'],
            ],
        ]);

    expect($response)
        ->toBeInstanceOf(AirtimeResponse::class)
        ->sent->toBe(1)
        ->error->toBe('None')
        ->responses->toBeArray()->toHaveCount(1)
        ->totalAmount->toBe('KES 96.0000')
        ->totalDiscount->toBe('KES 4.0000');
});

it('can query a transaction status', function (): void {
    MockClient::global([
        StatusQueryRequest::class => MockResponse::fixture('airtime/status-query'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->status('ATQid_therandomstringfromatwouldgohere');

    expect($response)
        ->toBeArray()
        ->toHaveKey('status');
});

it('throws if no recipients are set', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->airtime()
        ->send();
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'You must specify at least one recipient',
);
