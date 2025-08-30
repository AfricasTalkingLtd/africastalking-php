<?php

declare(strict_types=1);


use Africastalking\Africastalking;
use Africastalking\DTO\Response\VoiceCallResponse;
use Africastalking\DTO\Response\VoiceQueueStatus;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\Voice\CallRequest;
use Africastalking\Saloon\Voice\QueueStatusRequest;
use Africastalking\Services\Voice;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->voice();

    expect($subject)
        ->toBeInstanceOf(Voice::class);
});

it('constructs the payload without actions', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->to(['+254700000000'])
        ->as('+254711082000')
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'from' => '+254711082000',
        'to' => ['+254700000000'],
    ]);
});

it('constructs the payload with actions', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->to(['+254700000000'])
        ->as('+254711082000')
        ->withActions([
            [
                'actionType' => 'Say',
                'message' => 'We Love Nerds',
            ],
        ])
        ->payload();

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'from' => '+254711082000',
        'to' => ['+254700000000'],
        'voiceActions' => [
            [
                'actionType' => 'Say',
                'message' => 'We Love Nerds',
            ],
        ],
    ]);
});

it('accepts to as a string', function (): void {
    $recipients = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->to('+254711082000')
        ->recipients;

    expect($recipients)->toBe(['+254711082000']);
});

it('sets the voiceActions', function (): void {
    $actions = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->withActions([
            [
                'actionType' => 'Say',
                'message' => 'We Love Nerds',
            ],
        ])
        ->actions;

    expect($actions)->toBe([
        [
            'actionType' => 'Say',
            'message' => 'We Love Nerds',
        ],
    ]);
});

it('sets the idempotency key', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->idempotent('39f9486a-4517-48c6-be9c-f4dd504deb69');

    expect($subject)
        ->idempotencyKey->toBe('39f9486a-4517-48c6-be9c-f4dd504deb69')
        ->isIdempotent()->toBeTrue();
});

test('`to` appends to the list of recipients', function (): void {
    $recipients = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->to('+254711082000')
        ->to('+254700082000')
        ->to(['+254766082000', '+254777082000'])
        ->recipients;

    expect($recipients)
        ->toBe([
            '+254711082000',
            '+254700082000',
            '+254766082000',
            '+254777082000',
        ]);
});

test('`to` removes duplicate recipients', function (): void {
    $recipients = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->to('+254711082000')
        ->to('+254711082000')
        ->to(['+254711082000', '+254777082000', '+254711082000'])
        ->recipients;

    expect($recipients)->toBe(['+254711082000', '+254777082000']);
});

it('merges the payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->payload([
            'from' => '+254711082000',
            'to' => [
                '+254701234567',
                '+254789012345',
                '+254789543210',
            ],
            'voiceActions' => [
                'actionType' => 'Say',
                'message' => 'We Love Nerds',
            ],
        ]);

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'from' => '+254711082000',
        'to' => [
            '+254701234567',
            '+254789012345',
            '+254789543210',
        ],
        'voiceActions' => [
            'actionType' => 'Say',
            'message' => 'We Love Nerds',
        ],
    ]);
});

test('passed payload overwrites object values', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->as('+254730731000')
        ->payload([
            'username' => $_ENV['USERNAME'],
            'from' => '+254711082000',
            'to' => [
                '+254701234567',
                '+254789012345',
                '+254789543210',
            ],
            'voiceActions' => [
                'actionType' => 'Say',
                'message' => 'We Love Nerdlings',
            ],
        ]);

    expect($request)->toBe([
        'username' => $_ENV['USERNAME'],
        'from' => '+254711082000',
        'to' => [
            '+254701234567',
            '+254789012345',
            '+254789543210',
        ],
        'voiceActions' => [
            'actionType' => 'Say',
            'message' => 'We Love Nerdlings',
        ],
    ]);
});

it('throws an exception if the callerId is missing', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->call([
            '+254701234567',
            '+254789012345',
            '+254789543210',
        ]);
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'You must specify the CallerId',
);

it('throws an exception if no recipient is provided', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->as('+254711082000')
        ->call();
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'You must specify at least one recipient',
);

it('accepts payload in the call()', function (): void {
    MockClient::global([
        '*' => MockResponse::fixture('voice/no-op'),
    ]);

    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->call(
            recipients: ['+254700000000'],
            callerId: $_ENV['VOICE_PHONE'],
        );
})->throwsNoExceptions();

it('makes a call with actions', function (): void {
    MockClient::global([
        CallRequest::class => MockResponse::fixture('voice/call'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->call(
            recipients: ['+254700000000'],
            callerId: $_ENV['VOICE_PHONE'],
            actions: [
                [
                    'actionType' => 'Say',
                    'text' => 'Hi there. This is a test call from Africa\'s Talking where we Love Nerds',
                ],
            ],
        );

    expect($response)
        ->toBeInstanceOf(VoiceCallResponse::class)
        ->queueSize->toBe(1)
        ->entries->toHaveCount(1)
        ->error->toBe('None');
});

it('can set parameters', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->voice();

    $subject->set('+254700082000', '+254711082000', ['a' => 'b']);

    expect($subject)->actions->toBe(['a' => 'b']);
});

test('fetching Queued calls fails if phoneNumber is not set', function (): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->fetchQueuedCalls();
})->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'The phone number cannot be empty',
);

it('fetches Queued calls using phoneNumber parameter', function (): void {
    MockClient::global([
        QueueStatusRequest::class => MockResponse::fixture('voice/queue-status'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->fetchQueuedCalls($_ENV['VOICE_PHONE']);

    expect($response)->toBeInstanceOf(VoiceQueueStatus::class);
});

it('fetches Queued calls using credentials object', function (): void {
    MockClient::global([
        QueueStatusRequest::class => MockResponse::fixture('voice/queue-status'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->voice()
        ->as('+254711082000')
        ->fetchQueuedCalls();

    expect($response)->toBeInstanceOf(VoiceQueueStatus::class);
});
