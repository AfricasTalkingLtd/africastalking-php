<?php

namespace AfricasTalking\SDK;

class Token extends Service
{
    public function generateAuthToken()
    {
        $requestData = json_encode(['username' => $this->username]);
		$response = $this->client->post('auth-token/generate', ['body' => $requestData ] );
		return $this->success($response);
    }
}
