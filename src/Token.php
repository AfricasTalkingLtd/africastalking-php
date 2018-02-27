<?php

namespace AfricasTalking\SDK;

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
//         ```POST /auth-token/generate HTTP/1.1
// Host: api.africastalking.com
// Content-Type: application/json
// apiKey: SOME_API_KEY

// { "username": "SOME_USERNAME" }```
        // TODO
    }
}