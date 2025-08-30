<?php

declare(strict_types=1);

use Africastalking\DTO\Credentials;

it('can constructed', function (): void {
    $subject = new Credentials(
        username: 'username',
        apiKey: 'api-key',
        senderId: 'TEST',
        voicePhone: '+2547000000',
        bundlesProduct: 'Mobile Data',
    );

    expect($subject)
        ->toBeInstanceOf(Credentials::class)
        ->username->toBe('username')
        ->apiKey->toBe('api-key')
        ->senderId->toBe('TEST')
        ->voicePhone->toBe('+2547000000')
        ->bundlesProduct->toBe('Mobile Data');
});

it('can constructed with only username ans apiKey', function (): void {
    $subject = new Credentials('username', 'api-key');

    expect($subject)
        ->toBeInstanceOf(Credentials::class)
        ->username->toBe('username')
        ->apiKey->toBe('api-key');
});

it('checks for sandbox', function (string $username): void {
    $subject = new Credentials($username, 'api-key');

    expect($subject)
        ->isSandbox()->toBeTrue()
        ->isLive()->toBeFalse();
})->with([
    'sandbox',
    'Sandbox',
    'SANDBOX',
    'sandBox',
]);

it('checks for live credentials', function (): void {
    $subject = new Credentials('cool-app', 'api-key');

    expect($subject)
        ->isSandbox()->toBeFalse()
        ->isLive()->toBeTrue();
});
