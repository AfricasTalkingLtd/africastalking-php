<?php

namespace AfricasTalking\SDK;

class Account extends Service
{
    public function doFetchAccount()
    {
		$response = $this->client->get('user', ['query' => ['username'=> $this->username]]);        
		return $this->success($response);
    }

    public function fetchAccount()
    {
        return $this->doFetchAccount();
    }
}