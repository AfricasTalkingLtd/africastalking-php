<?php

namespace AfricasTalking;

class Token extends Service
{
    public function createCheckoutToken($phoneNumber)
    {
        $requestData = ['phoneNumber' => $phoneNumber, 'username' => $this->username];
		$response = $this->client->post('https://api.sandbox.africastalking.com/checkout/token/create', ['form_params' => $requestData]);
		return $this->success($response);
    }

    public function generateAuthToken()
    {
        // TODO
    }
}