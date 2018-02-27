<?php
namespace AfricasTalking\SDK;

class SMS extends Service
{
	protected function doSend ($options, $isBulk, $isPremium)
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

		if ($isBulk === true){
			$data['enqueue'] = 1;
		}

		if ($isPremium === true){
			if (empty($options['keyword']) || empty($options['linkId']) || empty($options['from'])) {
				return [
					'status' => 'error', 
					'data' => 'sender, keyword and linkId are required for premium SMS'
				];
			}

			$data['keyword'] = $options['keyword'];
			$data['linkId'] = $options['linkId'];

			// turn off bulk sms mode
			$data['bulkSMSMode'] = 0;

			if (!empty($options['retryDurationInHours'])) {
				$data['retryDurationInHours'] = $options['retryDurationInHours'];
			}
		}

		if (!empty($options['from'])) {
			$data['from'] = $options['from'];
		}

		$response = $this->client->post('messaging', ['form_params' => $data ]);

		return $this->success($response);
	}

	public function send($options) 
	{
		return $this->doSend($options, false, false);
	}

	public function sendBulk($options) 
	{
		return $this->doSend($options, true, false);
	}

	public function sendPremium($options) 
	{
		return $this->doSend($options, true, true);
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
		if(empty($options['phoneNumber']) || empty($options['shortCode']) || empty($options['keyword'])) {
			return $this->error("phoneNumber, shortCode and keyword must be specified");
		}

		// create checkout token
		$checkout_response = $this->client->post('/checkout/token/create', ['form_params' => 
			['phoneNumber' => $options['phoneNumber'] 
		]]);

		$data = [
			'username' 		=> $this->username,
			'phoneNumber' 	=> $options['phoneNumber'],
			'shortCode'		=> $options['shortCode'],
			'keyword' 		=> $options['keyword'],
			'checkoutToken'	=> json_decode($checkout_response->getBody()->getContents(), true)['token']
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