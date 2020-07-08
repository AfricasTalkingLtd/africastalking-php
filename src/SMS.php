<?php
namespace AfricasTalking\SDK;

class SMS extends Service
{
	protected $content;

	public function __construct($client, $username, $apiKey, $content)
	{
		parent::__construct($client, $username, $apiKey);
		$this->content = $content;
	}

	public function send($options)
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

		if (!empty($options['from'])) {
			$data['from'] = $options['from'];
		}

		$response = $this->client->post('messaging', ['form_params' => $data ]);

		return $this->success($response);
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

	public function sendPremium($options)
	{
		return $this->content->send($options);
	}

	public function createSubscription ($options)
	{
		return $this->content->createSubscription($options);
	}

	public function deleteSubscription ($options)
	{
		return $this->content->deleteSubscription($options);
	}

	public function fetchSubscriptions($options)
	{
		return $this->content->fetchSubscriptions($options);
	}
}
