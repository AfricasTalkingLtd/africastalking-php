<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\WalletBalanceResponse;
use Africastalking\Saloon\MobileData\SendRequest;
use Africastalking\Saloon\MobileData\StatusQueryRequest;
use Africastalking\Saloon\MobileData\WalletBalanceRequest;
use Africastalking\Services\MobileData;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->mobileData();

    expect($subject)->toBeInstanceOf(MobileData::class);
});

it('uses the correct baseUrl', function (string $username, string $baseUrl): void {
    $subject = Africastalking::make(
        $username,
        $_ENV['API_KEY'],
    )->mobileData();

    expect($subject)
        ->baseUrl()->toBe($baseUrl);
})->with([
    ['sandbox', 'https://bundles.sandbox.africastalking.com'],
    ['cool-app', 'https://bundles.africastalking.com'],
]);

it('constructs the payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'], $_ENV['PRODUCT_NAME'])
        ->mobileData()
        ->to('+254711082000', 1, 'GB', 'Weekly')
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'productName' => $_ENV['PRODUCT_NAME'],
        'recipients' => [
            [
                'phoneNumber' => '+254711082000',
                'quantity' => 1,
                'unit' => 'GB',
                'validity' => 'Weekly',
                'metadata' => ['phoneNumber' => '+254711082000'],
            ],
        ],
    ]);
});


it('can set multiple recipients', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'], $_ENV['PRODUCT_NAME'])
        ->mobileData()
        ->to('+254711082000', 1, 'GB', 'Weekly')
        ->to('+254700000000', 100, 'MB', 'Daily')
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'productName' => $_ENV['PRODUCT_NAME'],
        'recipients' => [
            [
                'phoneNumber' => '+254711082000',
                'quantity' => 1,
                'unit' => 'GB',
                'validity' => 'Weekly',
                'metadata' => ['phoneNumber' => '+254711082000'],
            ],
            [
                'phoneNumber' => '+254700000000',
                'quantity' => 100,
                'unit' => 'MB',
                'validity' => 'Daily',
                'metadata' => ['phoneNumber' => '+254700000000'],
            ],
        ],
    ]);
});

it('accepts an array of phoneNumbers', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'], $_ENV['PRODUCT_NAME'])
        ->mobileData()
        ->to(['+254711082000', '+254700000000'], 1, 'GB', 'Weekly')
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'productName' => $_ENV['PRODUCT_NAME'],
        'recipients' => [
            [
                'phoneNumber' => '+254711082000',
                'quantity' => 1,
                'unit' => 'GB',
                'validity' => 'Weekly',
                'metadata' => ['phoneNumber' => '+254711082000'],
            ],
            [
                'phoneNumber' => '+254700000000',
                'quantity' => 1,
                'unit' => 'GB',
                'validity' => 'Weekly',
                'metadata' => ['phoneNumber' => '+254700000000'],
            ],
        ],
    ]);
});

it('sets the idempotency key', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->mobileData()
        ->idempotent('39f9486a-4517-48c6-be9c-f4dd504deb69');

    expect($subject)
        ->idempotencyKey->toBe('39f9486a-4517-48c6-be9c-f4dd504deb69')
        ->isIdempotent()->toBeTrue();
});

it('sends data bundles to a single recipient', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('mobile-data/single-recipient'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'], $_ENV['PRODUCT_NAME'])
        ->mobileData()
        ->to('+254711082000', 15, 'MB', 'Day')
        ->send();

    expect($response)
        ->toBeArray()
        ->not->toBeEmpty()
        ->toHaveKey('entries')
        ->and($response['entries'])
        ->toHaveCount(1);
});

it('sends data bundles to multiple recipients', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('mobile-data/multiple-recipients'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'], $_ENV['PRODUCT_NAME'])
        ->mobileData()
        ->to('+254711082000', 1, 'GB', 'Month')
        ->to('+256783879001', 1.5, 'GB', 'Month')
        ->send();

    expect($response)
        ->toBeArray()
        ->not->toBeEmpty()
        ->toHaveKey('entries')
        ->and($response['entries'])
        ->toHaveCount(2);
});

it('can query a transaction status', function (): void {
    MockClient::global([
        StatusQueryRequest::class => MockResponse::fixture('mobile-data/status-query'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->mobileData()
        ->status('ATPxid_therandomstringfromatwouldgohere');

    expect($response)
        ->toBeArray()
        ->toHaveKey('status')
        ->toHaveKey('data')
        ->and($response['data'])
        ->toBeArray();
});

it('can fetch the wallet balance', function (): void {
    MockClient::global([
        WalletBalanceRequest::class => MockResponse::fixture('mobile-data/wallet-balance'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->mobileData()
        ->walletBalance();

    expect($response)
        ->toBeInstanceOf(WalletBalanceResponse::class)
        ->status->toBe('Success')
        ->currencyCode->not->toBeNull()
        ->amount->not->toBeNull();
});

it('does not explode when the wallet is not available', function (): void {
    MockClient::global([
        WalletBalanceRequest::class => MockResponse::fixture('mobile-data/wallet-not-available'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->mobileData()
        ->walletBalance();

    expect($response)
        ->toBeInstanceOf(WalletBalanceResponse::class)
        ->status->toBe('NotAvailable')
        ->currencyCode->toBeNull()
        ->amount->toBe(0.0);
});
