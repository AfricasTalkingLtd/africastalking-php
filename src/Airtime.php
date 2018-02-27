<?php

namespace AfricasTalking\SDK;

class Airtime extends Service
{
	public function send($options)
	{		
		if(empty($options['recipients'])) {
			return $this->error("recipients must be specified");
		}

		if(!is_array($options['recipients'])) {
			return $this->error("recipients must be an array");
		}

		foreach ($options['recipients'] as $recipient){

			if(!is_array($recipient)) {
				return $this->error("every recipient must be an array");
			}

			if(!array_key_exists('amount', $recipient) || !array_key_exists('phoneNumber', $recipient)) {
				return $this->error("amount and phone number must be specified for each recipient");
			}

			if ( !preg_match('/^[a-zA-Z]{3} \d+(\.\d{1,2})?$/', $recipient['amount']) ) {
				return $this->error("must contain a currency followed by an amount between 10 and 10000");
			}

			$amount = (int) explode(" ", $recipient['amount'])[1];

			if ( $amount < 10 || $amount > 10000) {
				return $this->error("amount must be between 10 and 10000");
			}
		}

		$data = [
			'username' 		=> $this->username,
			'recipients' 	=> json_encode($options['recipients'])
		];

		$response = $this->client->post('airtime/send', ['form_params' => $data ] );

		return $this->success($response);
	}
}