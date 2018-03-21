<?php

namespace AfricasTalking\SDK;

class Token extends Service
{
    public function createCheckoutToken($phoneNumber)
    {
        $requestData = ['phoneNumber' => $phoneNumber, 'username' => $this->username];
		$response = $this->client->post('checkout/token/create', ['form_params' => $requestData]);
		return $this->success($response);
    }

    public function generateAuthToken()
    {
        $requestData = json_encode(['username' => $this->username]);
		$response = $this->client->post('auth-token/generate', ['body' => $requestData ] );
		return $this->success($response);
    }
}