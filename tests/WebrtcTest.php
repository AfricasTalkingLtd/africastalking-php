<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\CapabilityTokenResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\Voice\CapabilityTokenRequest;
use Africastalking\Services\Webrtc;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->webrtc();

    expect($subject)
        ->toBeInstanceOf(Webrtc::class);
});

it('constructs the payload fluently', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->webrtc()
        ->for('clientName')
        ->phoneNumber('+254711082000')
        ->disableIncoming()
        ->disableOutgoing()
        ->expire('420s')
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'clientName' => 'clientName',
        'phoneNumber' => '+254711082000',
        'incoming' => false,
        'outgoing' => false,
        'expire' => '420s',
    ]);
});

it('has sensible defaults', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->webrtc()
        ->for('clientName')
        ->phoneNumber('+254711082000')
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'clientName' => 'clientName',
        'phoneNumber' => '+254711082000',
    ]);
});

it('throws an exception when clientName is not set', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->webrtc()
        ->phoneNumber('+254711082000')
        ->token();
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'You must specify a Client Name to generate a capability token',
);

it('throws an exception when phoneNumber is not set', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->webrtc()
        ->for('Bond-007')
        ->token();
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'The phone number cannot be empty',
);

it('fetches a capability token', function (): void {
    MockClient::global([
        CapabilityTokenRequest::class => MockResponse::fixture('voice/webrtc-capability-token'),
    ]);

    $token = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->webrtc()
        ->phoneNumber($_ENV['VOICE_PHONE'])
        ->for('Bond-007')
        ->enableIncoming()
        ->enableOutgoing()
        ->expire('420s')
        ->token();

    expect($token)
        ->toBeInstanceOf(CapabilityTokenResponse::class)
        ->clientName->toBe('Bond-007')
        ->incoming->toBeTrue()
        ->outgoing->toBeTrue()
        ->token->toBeString()
        ->lifetime->toBe(420);
});

test('phoneNumber and agentName can be passed as params', function (): void {
    MockClient::global([
        CapabilityTokenRequest::class => MockResponse::fixture('voice/webrtc-capability-token'),
    ]);

    $token = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->webrtc()
        ->token('Bond-007', $_ENV['VOICE_PHONE']);

    expect($token)
        ->toBeInstanceOf(CapabilityTokenResponse::class)
        ->clientName->toBe('Bond-007')
        ->token->toBeString();
});
