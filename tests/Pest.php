<?php

declare(strict_types=1);

use Saloon\Config;
use Saloon\Http\Faking\MockClient;

uses()
    ->beforeEach(function (): void {
        MockClient::destroyGlobal();
        Config::preventStrayRequests();
    })
    ->in(__DIR__);
