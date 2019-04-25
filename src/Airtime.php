<?php

namespace AfricasTalking\SDK;

class Airtime extends Service
{
	public function send($parameters, $options = [])
	{		
		if(empty($parameters['recipients'])) {
			return $this->error("recipients must be specified");
		}

		if(!is_array($parameters['recipients'])) {
			return $this->error("recipients must be an array");
		}

		foreach ($parameters['recipients'] as $key=>$recipient){

			if(!is_array($recipient)) {
				return $this->error("every recipient must be an array");
			}

			if(!array_key_exists('phoneNumber', $recipient) || !array_key_exists('currencyCode', $recipient) || !array_key_exists('amount', $recipient)) {
				return $this->error("phoneNumber, currencyCode and amount must be specified for each recipient");
			}

			$currencyCode = $recipient['currencyCode'];
			if (strlen($currencyCode) != 3) {
				return $this->error('currencyCode must be in 3-digit ISO format');
			}

            $recipient['amount'] = $recipient['currencyCode'].' '.$recipient['amount'];

            unset($recipient['currencyCode']);
 
            $parameters['recipients'][$key] = $recipient;
		}

		$data = [
			'username' 		=> $this->username,
			'recipients' 	=> json_encode($parameters['recipients'])
		];

        $requestOptions = [
            'form_params' => $data,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

		$response = $this->client->post('airtime/send', $requestOptions);

		return $this->success($response);
	}
}
