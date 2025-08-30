<?php

declare(strict_types=1);

namespace Africastalking\Services;

use Africastalking\DTO\Response\ApplicationBalance;
use Africastalking\Saloon\AfricastalkingConnector;
use Africastalking\Saloon\Application\BalanceRequest;

class Application extends Service
{
    public function bal(): ApplicationBalance
    {
        return $this->balance();
    }
    public function balance(): ApplicationBalance
    {
        $connector = new AfricastalkingConnector($this->credentials);

        return $connector->send(
            new BalanceRequest($this->credentials->username),
        )->dto();
    }
}
