<?php

namespace AfricasTalking\SDK;

class Application extends Service
{
    public function doFetchApplication(): array
    {
        $response = $this->client->get('user', ['query' => ['username' => $this->username]]);

        return $this->success($response);
    }

    public function fetchApplicationData(): array
    {
        return $this->doFetchApplication();
    }
}
