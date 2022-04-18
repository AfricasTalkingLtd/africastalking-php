<?php

namespace AfricasTalking\SDK;

class Application extends Service
{
  public function __construct(ATClient $atClient)
  {
    $baseClient = $atClient->base();
    parent::__construct($baseClient, $atClient->username, $atClient->apiKey);
  }

  public function doFetchApplication()
  {
    $response = $this->client->get('user', ['query' => ['username' => $this->username]]);
    return $this->success($response);
  }

  public function fetchApplicationData()
  {
    return $this->doFetchApplication();
  }
}
