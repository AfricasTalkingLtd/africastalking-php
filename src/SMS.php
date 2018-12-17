<?php
namespace AfricasTalking\SDK;

class SMS extends Service
{
	protected function doSend ($options, $isPremium)
	{
		if (empty($options['to']) || empty($options['message'])) {
			return $this->error('recipient and message must be defined');
		}

		if (!is_array($options['to'])) {
			$options['to'] = [$options['to']];
		}

		$data = [
			'username' 	=> $this->username,
			'to' 		=> implode(",", $options['to']),
			'message' 	=> $options['message']
		];

		if (array_key_exists('enqueue', $options) && $options['enqueue']) {
			$data['enqueue'] = 1;
		}

		if ($isPremium === true) {
			if (empty($options['from'])) {
				return [
					'status' => 'error', 
					'data' => 'from is required for premium SMS'
				];
			}

			if (!empty($options['keyword'])) {
				$data['keyword'] = $options['keyword'];
			}

            if (!empty($options['linkId'])) {
                $data['linkId'] = $options['linkId'];
            }

			if (!empty($options['retryDurationInHours'])) {
				$data['retryDurationInHours'] = $options['retryDurationInHours'];
			}

			// turn off bulk sms mode
			$data['bulkSMSMode'] = 0;
		}

		if (!empty($options['from'])) {
			$data['from'] = $options['from'];
		}

		$response = $this->client->post('messaging', ['form_params' => $data ]);

		return $this->success($response);
	}

	public function send($options)
	{
		return $this->doSend($options, false);
	}

	public function sendPremium($options)
	{
		return $this->doSend($options, true);
	}

	public function fetchMessages($options = [])
	{
		if (empty($options['lastReceivedId'])) {
			$options['lastReceivedId'] = 0;
		}

		if (!is_numeric($options['lastReceivedId'])) {
			return $this->error('lastReceivedId must be an integer');
		}	

		$data = [
			'username' 			=> $this->username,
			'lastReceivedId' 	=> $options['lastReceivedId']
		];

		$response = $this->client->get('messaging', ['query' => $data ] );

		return $this->success($response);
	}

	public function createSubscription ($options)
	{
		if(empty($options['phoneNumber']) || empty($options['shortCode']) || empty($options['keyword']) || empty($options['checkoutToken'])) {
			return $this->error("phoneNumber, shortCode keyword and checkoutToken must be specified");
		}

		$data = [
			'username' 		=> $this->username,
			'phoneNumber' 	=> $options['phoneNumber'],
			'shortCode'		=> $options['shortCode'],
			'keyword' 		=> $options['keyword'],
			'checkoutToken'	=> $options['checkoutToken']
		];

		$response = $this->client->post('subscription/create', ['form_params' => $data ] );

		return $this->success($response);
	}

	public function deleteSubscription ($options)
	{
		if(empty($options['phoneNumber']) || empty($options['shortCode']) || empty($options['keyword'])) {
			return $this->error("phoneNumber, shortCode and keyword must be specified");
		}

		$data = [
			'username' 		=> $this->username,
			'phoneNumber' 	=> $options['phoneNumber'],
			'shortCode'		=> $options['shortCode'],
			'keyword' 		=> $options['keyword']
		];

		$response = $this->client->post('subscription/delete', ['form_params' => $data ] );

		return $this->success($response);
	}

	public function fetchSubscriptions($options)
	{
		if(empty($options['shortCode']) || empty($options['keyword'])) {
			return $this->error("shortCode and keyword must be specified");
		}

		if (empty($options['lastReceivedId'])) {
			$options['lastReceivedId'] = 0;
		}

		if (!is_numeric($options['lastReceivedId'])) {
			return $this->error('lastReceivedId must be an integer');
		}

		$data = [
			'username' 			=> $this->username,
			'lastReceivedId'	=> $options['lastReceivedId'],
			'shortCode'			=> $options['shortCode'],
			'keyword' 			=> $options['keyword']
		];	

		$response = $this->client->get('subscription', ['query' => $data ] );

		return $this->success($response);

	}
}
