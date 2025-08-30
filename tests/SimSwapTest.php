<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\SimSwapResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\Insights\SimSwapRequest;
use Africastalking\Services\Insights;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->insights();

    expect($subject)->toBeInstanceOf(Insights::class);
});

it('uses the correct baseUrl', function (string $username, string $baseUrl): void {
    $subject = Africastalking::make(
        $username,
        $_ENV['API_KEY'],
    )->insights();

    expect($subject)
        ->baseUrl()->toBe($baseUrl);
})->with([
    ['sandbox', 'https://insights.sandbox.africastalking.com/v1'],
    ['cool-app', 'https://insights.africastalking.com/v1'],
]);

it('constructs the payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->phoneNumbers(['+254711082000'])
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'phoneNumbers' => ['+254711082000'],
    ]);
});

it('can set multiple recipients', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->for(['+254711082000'])
        ->for(['+254700082000']);

    expect($subject)
        ->phoneNumbers->toBe(['+254711082000', '+254700082000'])
        ->payload()->toBe([
            'username' => $_ENV['USERNAME'],
            'phoneNumbers' => ['+254711082000', '+254700082000'],
        ]);
});

it('accepts string phoneNumbers', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->for('+254711082000')
        ->for('+254700082000');

    expect($subject)
        ->phoneNumbers->toBe(['+254711082000', '+254700082000'])
        ->payload()->toBe([
            'username' => $_ENV['USERNAME'],
            'phoneNumbers' => ['+254711082000', '+254700082000'],
        ]);
});

it('can removes duplicate recipients', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->for(['+254711082000'])
        ->for(['+254700082000'])
        ->for(['+254711082000'])
        ->for(['+254700082000'])
        ->for(['+254700081000']);

    expect($subject)
        ->phoneNumbers->toBe(['+254711082000', '+254700082000', '+254700081000'])
        ->payload()->toBe([
            'username' => $_ENV['USERNAME'],
            'phoneNumbers' => ['+254711082000', '+254700082000', '+254700081000'],
        ]);
});

it('sets the idempotency key', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->idempotent('39f9486a-4517-48c6-be9c-f4dd504deb69');

    expect($subject)
        ->idempotencyKey->toBe('39f9486a-4517-48c6-be9c-f4dd504deb69')
        ->isIdempotent()->toBeTrue();
});

it('sends airtime to a single recipient', function (): void {
    MockClient::global([
        SimSwapRequest::class => MockResponse::fixture('insights/simswap'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->for('+254711082000')
        ->send();

    expect($response)
        ->toBeInstanceOf(SimSwapResponse::class)
        ->responses->toHaveCount(1)
        ->status->toBe('Processed')
        ->cost->toBe(0.05)
        ->currencyCode->toBe('USD');
});

it('handles errors when product does not exist', function (string $username): void {
    MockClient::global([
        SimSwapRequest::class => MockResponse::fixture('insights/no-product-created'),
    ]);

    $response = Africastalking::make($username, $_ENV['API_KEY'])
        ->insights()
        ->for('+254711082000')
        ->send();

    expect($response)
        ->toBeInstanceOf(SimSwapResponse::class)
        ->responses->toHaveCount(0)
        ->status->toBe('ProductNotFound')
        ->cost->toBeNull()
        ->currencyCode->toBeNull();
})->with(['sandbox', $_ENV['USERNAME']]);

it('throws if no recipients are set', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->insights()
        ->send();
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'You must specify at least one recipient',
);
