<?php

declare(strict_types=1);

use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Services\Voice;
use Saloon\Http\Request;

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'ddd'])
    ->not->toBeUsed();

test('DTOs')
    ->expect("Africastalking\DTO")
    ->toBeClasses()
    ->toBeReadonly()
    ->not->toBeFinal()
    ->not->toHaveProtectedMethods()
    ->not->toHavePrivateMethods();

test('DTO Responses')
    ->expect('Africastalking\DTO\Response')
    ->toExtend('Africastalking\DTO\Response\ATResponse');

test('Services')
    ->expect('Africastalking\Services')
    ->toExtend('Africastalking\Services\Service')
    ->not->toBeReadonly()
    ->not->toBeFinal()
    ->not->toHaveProtectedMethods()
    ->ignoring(Voice::class)
    ->not->toHavePrivateMethods();

test('Traits')
    ->expect("Africastalking\Traits")
    ->toBeTraits()
    ->not->toBeReadonly()
    ->not->toBeFinal()
    ->not->toHaveProtectedMethods()
    ->not->toHavePrivateMethods()
    ->not->toHaveConstructor();

test('Saloon')
    ->expect("Africastalking\Saloon")
    ->toBeClasses()
    ->not->toBeReadonly()
    ->not->toBeFinal()
    ->not->toHavePrivateMethods()
    ->toExtend(Request::class)
    ->ignoring(AfricastalkingConnector::class)
    ->toHaveSuffix('Request')
    ->ignoring(AfricastalkingConnector::class);

test('Exceptions')
    ->expect("Africastalking\Exceptions")
    ->toBeClasses()
    ->toBeFinal()
    ->toExtend(Exception::class);

arch()->preset()->security();
arch()->preset()->php();
