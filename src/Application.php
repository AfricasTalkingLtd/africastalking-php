<?php

namespace AfricasTalking\SDK;

class Application extends Service
{
    public function doFetchApplication()
    {
		$response = $this->client->get('user', ['query' => ['username'=> $this->username]]);        
		return $this->success($response);
    }

    public function fetchApplication()
    {
        return $this->doFetchApplication();
    }
}
