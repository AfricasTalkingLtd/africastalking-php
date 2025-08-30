<?php

declare(strict_types=1);

use Africastalking\Africastalking;
use Africastalking\DTO\Response\ATResponse;
use Africastalking\DTO\Response\MessageResponse;
use Africastalking\Exceptions\AfricastalkingException;
use Africastalking\Saloon\BulkSms\SandboxRequest;
use Africastalking\Saloon\BulkSms\SendRequest;
use Africastalking\Services\Service;
use Africastalking\Services\SMS;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can be initialized', function (): void {
    $subject = Africastalking::make(
        $_ENV['USERNAME'],
        $_ENV['API_KEY'],
    )->sms();

    expect($subject)
        ->toBeInstanceOf(SMS::class);
});

it('constructs the payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->to(['+254711082000'])
        ->as('SENDER')
        ->message('We Love Nerds and Nerdlings!')
        ->payload();

    expect($request)->toBe([
        'senderId' => 'SENDER',
        'phoneNumbers' => ['+254711082000'],
        'message' => 'We Love Nerds and Nerdlings!',
        'username' => $_ENV['USERNAME'],
    ]);
});

it('accepts to as a string', function (): void {
    $recipients = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->to('+254711082000')
        ->recipients;

    expect($recipients)->toBe(['+254711082000']);
});

it('sets the idempotency key', function (): void {
    $subject = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->idempotent('39f9486a-4517-48c6-be9c-f4dd504deb69');

    expect($subject)
        ->idempotencyKey->toBe('39f9486a-4517-48c6-be9c-f4dd504deb69')
        ->isIdempotent()->toBeTrue();
});

test('`to` appends to the list of recipients', function (): void {
    $recipients = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
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
        ->sms()
        ->to('+254777082000')
        ->to('+254711082000')
        ->to(['+254711082000', '+254777082000', '+254711082000'])
        ->recipients;

    expect($recipients)->toBe(['+254777082000', '+254711082000']);
});

it('merges the payload for live', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->payload([
            'senderId' => 'SENDER',
            'phoneNumbers' => [
                '+25470123456',
                '+254789012345',
                '+254789543210',
            ],
            'message' => 'We Love Nerds and Nerdlings!',
        ]);

    expect($request)->toBe([
        'senderId' => 'SENDER',
        'phoneNumbers' => [
            '+25470123456',
            '+254789012345',
            '+254789543210',
        ],
        'message' => 'We Love Nerds and Nerdlings!',
        'username' => $_ENV['USERNAME'],
    ]);
});

it('merges the payload for sandbox', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->sandboxPayload([
            'from' => 'SENDER',
            'to' => '+25470123456,+254789012345,+254789543210',
            'message' => 'We Love Nerds and Nerdlings!',
        ]);

    expect($request)->toBe([
        'from' => 'SENDER',
        'to' => '+25470123456,+254789012345,+254789543210',
        'message' => 'We Love Nerds and Nerdlings!',
        'username' => $_ENV['USERNAME'],
    ]);
});

it('builds live payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->as('SENDER')
        ->to(['+25470123456', '+254789012345', '+254789543210',])
        ->message('We Love Nerds and Nerdlings!')
        ->hashed('+efbe710c7b206e6ed5df46eadc8ef003ec13c20d5c59e7d24b4b97be37af2274')
        ->payload();

    expect($request)->toBe([
        'senderId' => 'SENDER',
        'phoneNumbers' => [
            '+25470123456',
            '+254789012345',
            '+254789543210',
        ],
        'message' => 'We Love Nerds and Nerdlings!',
        'maskedNumber' => '+efbe710c7b206e6ed5df46eadc8ef003ec13c20d5c59e7d24b4b97be37af2274',
        'username' => $_ENV['USERNAME'],
    ]);
});

it('builds sandbox payload', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->as('MY_SENDER_ID')
        ->to(['+25470123456', '+254789012345', '+254789543210',])
        ->message('We Love Nerds and Nerdlings!')
        ->hashed('+efbe710c7b206e6ed5df46eadc8ef003ec13c20d5c59e7d24b4b97be37af2274')
        ->sandboxPayload();

    expect($request)->toBe([
        'from' => 'MY_SENDER_ID',
        'to' => '+25470123456,+254789012345,+254789543210',
        'message' => 'We Love Nerds and Nerdlings!',
        'username' => $_ENV['USERNAME'],
    ]);
});

it('setting an empty senderId defaults to null', function (): void {
    $request = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->as('')
        ->to(['+25470123456', '+254789012345', '+254789543210',])
        ->message('We Love Nerds and Nerdlings!')
        ->payload();

    expect($request)->toBe([
        'senderId' => null,
        'phoneNumbers' => ['+25470123456', '+254789012345', '+254789543210'],
        'message' => 'We Love Nerds and Nerdlings!',
        'username' => $_ENV['USERNAME'],
    ]);
});

it('throws an exception if the message is missing', function (string $username): void {
    Africastalking::make($username, $_ENV['API_KEY'])
        ->sms()
        ->send([
            'senderId' => 'SENDER',
            'phoneNumbers' => [
                '+25470123456',
                '+254789012345',
                '+254789543210',
            ],
        ]);
})->with(['live', 'sandbox'])->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'The message cannot be empty',
);

it('throws an exception if no recipient is provided', function (string $username): void {
    Africastalking::make($username, $_ENV['API_KEY'])
        ->sms()
        ->as('SENDER')
        ->message('We Love Nerds and Nerdlings!')
        ->send();
})->with(['live', 'sandbox'])->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'You must specify at least one recipient',
);

it('throws an exception for invalid hashed phoneNumbers', function (string $phone): void {
    Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->as('SENDER')
        ->hashed($phone)
        ->message('We Love Nerds and Nerdlings!')
        ->send();
})->with(['+254722000000'])->with(['live', 'sandbox'])->throws(
    exception: AfricastalkingException::class,
    exceptionMessage: 'The hashed number must be 64 characters preceded with a +',
);

it('extends base service class')
    ->expect(SMS::class)
    ->toExtend(Service::class)
    ->toHaveMethod(['idempotent']);

it('sends a message', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('messaging/bulk/single'),
        SandboxRequest::class => MockResponse::fixture('messaging/bulk/single'),
    ]);

    $response = Africastalking::make('fake', 'secret')
        ->sms()
        ->to('+254730731000')
        ->as($_ENV['SENDER_ID'])
        ->idempotent('unique-1')
        ->message('We Love Nerds and Nerdlings!')
        ->send();

    expect($response)
        ->toBeInstanceOf(ATResponse::class)
        ->toBeInstanceOf(MessageResponse::class)
        ->message->toBe('Sent to 1/1 Total Cost: KES 0.8000')
        ->recipients->toHaveCount(1);
});

it('sends a sandbox message', function (): void {
    Config::preventStrayRequests();

    MockClient::global([
        SandboxRequest::class => MockResponse::fixture('messaging/bulk/single'),
    ]);

    $response = Africastalking::make('sandbox', 'secret')
        ->sms()
        ->to('+254730731000')
        ->as($_ENV['SENDER_ID'])
        ->message('We Love Nerds and Nerdlings!')
        ->send();

    expect($response)
        ->toBeInstanceOf(ATResponse::class)
        ->toBeInstanceOf(MessageResponse::class)
        ->message->toBe('Sent to 1/1 Total Cost: KES 0.8000')
        ->recipients->toHaveCount(1);
});

it('sends a message to multiple recipients', function (): void {
    MockClient::global([
        SendRequest::class => MockResponse::fixture('messaging/bulk/multiple'),
        SandboxRequest::class => MockResponse::fixture('messaging/bulk/multiple'),
    ]);

    $response = Africastalking::make($_ENV['USERNAME'], $_ENV['API_KEY'])
        ->sms()
        ->to(['+254730731000', '+254700000000'])
        ->as($_ENV['SENDER_ID'])
        ->message('We Love Nerds and Nerdlings!')
        ->send();

    expect($response)
        ->toBeInstanceOf(ATResponse::class)
        ->toBeInstanceOf(MessageResponse::class)
        ->message->toBe('Sent to 2/2 Total Cost: KES 1.6000')
        ->recipients->toHaveCount(2);
});
