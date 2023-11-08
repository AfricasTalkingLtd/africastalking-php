<?php
namespace AfricasTalking\SDK;

class MobileData extends Service
{

    public function __call($method, $args)
    {
        // First check if method exists
        if (method_exists($this, 'do' . $method)) {
            $func = 'do' . $method;
            if (!isset($args[0])) {
                $args = [ 0 => ''];
            }
            return $this->$func($args[0]);
        } else {
            return $this->error($method .' is an invalid Mobile Data SDK Method');
        }
    }

    protected function doSend($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];

        // Check if recipients array is provided
        if (!isset($parameters['recipients'])) {
            return $this->error('recipients must be an array containing phoneNumber, unit, quatity, validity and metadata');
        } else if (isset($parameters['recipients']) && is_array($parameters['recipients'])) {
            $recipients = $parameters['recipients'];
            
            foreach ($recipients as $r) {
                if (!isset($r['phoneNumber']) || 
                    !isset($r['quantity']) || 
                    !isset($r['unit']) ||
                    !isset($r['validity']) ||
                    !isset($r['metadata'])) {

                    return $this->error('recipients must be an array containing phoneNumber, quantity, unit, validity and metadata');
                }

                if (isset($r['validity'])) {
                    if (!in_array($r['validity'], ['Day', 'Month', 'Week'])) {
                        return $this->error('validity must be one of Day, Week, Month'); 
                    }
                }

                if (isset($r['unit'])) {
                    if (!in_array($r['unit'], ['MB', 'GB'])) {
                        return $this->error('unit must be one of MB, GB'); 
                    }
                }
            }
        }

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'recipients' => $recipients,
        ];

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        $response = $this->client->post('mobile/data/request', $requestOptions);
        return $this->success($response);
    }

    protected function doFindTransaction($options)
    {
        if (!isset($options['transactionId'])) {
            return $this->error('transactionId must be defined');
        }

        $requestData = [
            'username' => $this->username,
            'transactionId' => $options['transactionId']
        ];

        $response = $this->client->get('query/transaction/find', ['query' => $requestData]);
        return $this->success($response);
    }

    protected function doFetchWalletBalance()
    {
        $requestData = [
            'username' => $this->username
        ];

        $response = $this->client->get('query/wallet/balance', ['query' => $requestData]);
        return $this->success($response);
    }
}
