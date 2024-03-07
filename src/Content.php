<?php

namespace AfricasTalking\SDK;

class Content extends Service
{
    public function send ($options)
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

        if (empty($options['from'])) {
            return [
                'status' => 'error', 
                'data' => 'from is required for premium SMS'
            ];
        } else {
            $data['from'] = $options['from'];
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

        $response = $this->client->post('messaging', ['form_params' => $data ]);

		return $this->success($response);
    }

    public function createSubscription ($options)
	{
        if (empty($options['phoneNumber']) ||
            empty($options['shortCode']) ||
            empty($options['keyword'])) {
			return $this->error("phoneNumber, shortCode and keyword must be specified");
		}

		$data = [
			'username' 		=> $this->username,
			'phoneNumber' 	=> $options['phoneNumber'],
			'shortCode'		=> $options['shortCode'],
			'keyword' 		=> $options['keyword']
		];

        /**
         * checkoutToken Key was removed in commit:339f7057d8ff640ffa9802b4d3a812848b1072a9.
         * To prevent breaking applications in production, we conditionally add it to
         * the request otherwise previous behaviour persists.
         **/

        if(array_key_exists('checkoutToken',$options)){
            $data['checkoutToken'] = $options['checkoutToken'];
        }

		$response = $this->client->post('subscription/create', ['form_params' => $data ] );

		return $this->success($response);
	}

	public function deleteSubscription ($options)
	{
        if (empty($options['phoneNumber']) ||
            empty($options['shortCode']) ||
            empty($options['keyword'])) {
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
