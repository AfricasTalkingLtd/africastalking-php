<?php
namespace AfricasTalking\SDK;

class Payments extends Service
{

    const REASON = [
        'SALARY' => 'SalaryPayment',
        'SALARY_WITH_CHARGE' => 'SalaryPaymentWithWithdrawalChargePaid',
        'BUSINESS' => 'BusinessPayment',
        'BUSINESS_WITH_CHARGE' => 'BusinessPaymentWithWithdrawalChargePaid',
        'PROMOTION' => 'PromotionPayment'
    ];

    const PROVIDER = [
        'ATHENA' => 'Athena',
        'MPESA' => 'Mpesa',
    ];

    const TRANSFER_TYPE = [
      'BUY_GOODS'  => 'BusinessBuyGoods',
      'PAYBILL'  => 'BusinessPayBill',
      'DISBURSE_FUNDS'  => 'DisburseFundsToBusiness',
      'B2B_TRANSFER'  => 'BusinessToBusinessTransfer'
    ];

    const BANK = [
      'FCMB_NG' => 234001,
      'ZENITH_NG' => 234002,
      'ACCESS_NG' => 234003,
      'GTBANK_NG' => 234004,
      'ECOBANK_NG' => 234005,
      'DIAMOND_NG' => 234006,
      'PROVIDUS_NG' => 234007,
      'UNITY_NG' => 234008,
      'STANBIC_NG' => 234009,
      'STERLING_NG' => 234010,
      'PARKWAY_NG' => 234011,
      'AFRIBANK_NG' => 234012,
      'ENTREPRISE_NG' => 234013,
      'FIDELITY_NG' => 234014,
      'HERITAGE_NG' => 234015,
      'KEYSTONE_NG' => 234016,
      'SKYE_NG' => 234017,
      'STANCHART_NG' => 234018,
      'UNION_NG' => 234019,
      'UBA_NG' => 234020,
      'WEMA_NG' => 234021,
      'FIRST_NG' => 234022,
    ];

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
            return $this->error($method .' is an invalid Payments SDK Method');
        }
    }
    
    protected function doCardCheckoutCharge($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];

        // Check if currencyCode is set
        if (!isset($parameters['currencyCode'])) {
            return $this->error('currencyCode must be defined');
        } else {
            $currencyCode = $parameters['currencyCode'];
            if (strlen($currencyCode) != 3) {
                return $this->error('currencyCode must be in 3-digit ISO format');
            }
        }

        // Check if amount is set
        if (!isset($parameters['amount'])) {
            return $this->error('amount must be defined');
        }
        $amount = $parameters['amount'];

        // Check if narration is set
        if (!isset($parameters['narration'])) {
            return $this->error('narration must be defined');
        }
        $narration = $parameters['narration'];

        // Check if metadata is set
        if (isset($parameters['metadata']) && !is_array($parameters['metadata'])) {
            return $this->error('please provide metadata as an array');
        }

        // Check if paymentCard is provided
        if (!isset($parameters['paymentCard']) && !is_array($parameters['paymentCard'])) {
            return $this->error('paymentCard must be an array containing, 
            number, countryCode, cvvNumber, expiryMonth, expiryYear and authToken');
        } else if (isset($parameters['paymentCard']) && is_array($parameters['paymentCard'])) {
            $paymentCard = $parameters['paymentCard'];
            if (!isset($paymentCard['number']) || 
            !isset($paymentCard['countryCode']) || 
            !isset($paymentCard['cvvNumber']) || 
            !isset($paymentCard['expiryMonth']) || 
            !isset($paymentCard['expiryYear']) || 
            !isset($paymentCard['authToken'])) {

                return $this->error('paymentCard must be an array containing, number, countryCode, cvvNumber, expiryMonth, expiryYear and authToken');
            }
        }

        // Check if both paymentCard and checkoutToken are not both set
        if (isset($parameters['paymentCard']) && isset($parameters['checkoutToken'])) {
            return $this->error('When using a checkoutToken, paymentCard option should NOT be populated');
        }

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'currencyCode' => $currencyCode,
            'amount' => $amount,
            'narration' => $narration
        ];

        if (isset($parameters['metadata'])) {
            if (!empty($metadata) && is_array($metadata)) {
                $requestData['metadata'] = $metadata;
            }
        }

        if (!empty($checkoutToken)) {
            $requestData['checkoutToken'] = $checkoutToken;
        }

        if (!empty($paymentCard)) {
            $requestData['paymentCard'] = $paymentCard;
        }

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        $response = $this->client->post('card/checkout/charge', $requestOptions);
        return $this->success($response);
    }

    protected function doCardCheckoutValidate($options)
    {
        // Check if transactionId is set
        if (!isset($options['transactionId'])) {
            return $this->error('transactionId must be defined');
        }
        $transactionId = $options['transactionId'];

        // Check if otp is set
        if (!isset($options['otp'])) {
            return $this->error('otp must be defined');
        }
        $otp = $options['otp'];

        $requestData = [
            'username' => $this->username,
            'transactionId' => $transactionId,
            'otp' => $otp
        ];

        $response = $this->client->post('card/checkout/validate', ['json' => $requestData]);
        return $this->success($response);       
    }

    protected function doBankCheckoutCharge($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];
                
        // Check if currencyCode is set
        if (!isset($parameters['currencyCode'])) {
            return $this->error('currencyCode must be defined');
        } else {
            $currencyCode = $parameters['currencyCode'];
            if (strlen($currencyCode) != 3) {
                return $this->error('currencyCode must be in 3-digit ISO format');
            }
        }

        // Check if amount is set
        if (!isset($parameters['amount'])) {
            return $this->error('amount must be defined');
        }
        $amount = $parameters['amount'];

        // Check if narration is set
        if (!isset($parameters['narration'])) {
            return $this->error('narration must be defined');
        }
        $narration = $parameters['narration'];

        // Check if metadata is set
        if (isset($parameters['metadata'])) {
            $metadata = $parameters['metadata'];
            if (!empty($metadata) && !is_array($metadata)) {
                $this->error('please provide metadata as an array');
            }
        }

        // Check if bankAccount is provided
        if (!isset($parameters['bankAccount']) && !is_array($parameters['bankAccount'])) {
            return $this->error('bankAccount must be an array containing, 
            number, countryCode, cvvNumber, expiryMonth, expiryYear and authToken');
        } else if (isset($parameters['bankAccount']) && is_array($parameters['bankAccount'])) {
            $bankAccount = $parameters['bankAccount'];
            if (!isset($bankAccount['accountNumber']) || 
                !isset($bankAccount['bankCode']) || 
                !isset($bankAccount['dateOfBirth'])) {

                return $this->error('bankAccount must be an array containing, accountNumber, bankCode, dateOfBirth');
            }
        }

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'currencyCode' => $currencyCode,
            'amount' => $amount,
            'narration' => $narration
        ];

        // JSON encode any metadata
        if (!empty($metadata) && is_array($metadata)) {
            $requestData['metadata'] = $metadata;
        }

        if (!empty($bankAccount)) {
            $requestData['bankAccount'] = $bankAccount;
        }

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        $response = $this->client->post('bank/checkout/charge', $requestOptions);
        return $this->success($response);
    }

    protected function doBankCheckoutValidate($options)
    {
        // Check if transactionId is set
        if (!isset($options['transactionId'])) {
            return $this->error('transactionId must be defined');
        }
        $transactionId = $options['transactionId'];

        // Check if otp is set
        if (!isset($options['otp'])) {
            return $this->error('otp must be defined');
        }
        $otp = $options['otp'];

        $requestData = [
            'username' => $this->username,
            'transactionId' => $transactionId,
            'otp' => $otp
        ];

        $response = $this->client->post('bank/checkout/validate', ['json' => $requestData]);
        return $this->success($response);
    }

    protected function doBankTransfer($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];

        // Check if recipients array is provided
        if (!isset($parameters['recipients'])) {
            return $this->error('recipients must be an array containing, 
            bankAccount, currencyCode, amount, and narration');
        } else if (isset($parameters['recipients']) && is_array($parameters['recipients'])) {
            $recipients = $parameters['recipients'];
            foreach ($recipients as $r) {
                if (!isset($r['bankAccount']) || 
                    !isset($r['currencyCode']) || 
                    !isset($r['amount']) || 
                    !isset($r['narration'])) {          

                    return $this->error('recipients must be an array containing, 
                        bankAccount, currencyCode, amount, and narration');
                }

                // Check if bankAccount is provided
                $bankAccount = $r['bankAccount'];
                if (!empty($bankAccount) && !is_array($bankAccount)) {
                    return $this->error('bankAccount must be an array containing, 
                    number, countryCode, cvvNumber, expiryMonth, expiryYear and authToken');
                } else if (!empty($bankAccount) && is_array($bankAccount)) {
                    if (!isset($bankAccount['accountNumber']) || 
                        !isset($bankAccount['bankCode']) || 
                        !isset($bankAccount['dateOfBirth'])) {

                        return $this->error('bankAccount must be an array containing, 
                        number, countryCode, cvvNumber, expiryMonth, expiryYear and authToken');                            
                    }
                }
            }
        }

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'recipients' => $recipients
        ];

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        $response = $this->client->post('bank/transfer', $requestOptions);
        return $this->success($response);
    }

    protected function doMobileCheckout($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];

        // Validate phoneNumber
        if (!isset($parameters['phoneNumber'])) {
            return $this->error('phoneNumber must be defined');
        } else {
            $phoneNumber = $parameters['phoneNumber'];
            $checkPhoneNumber = strpos($phoneNumber, '+');
            if ($checkPhoneNumber === false || $checkPhoneNumber != 0) {
                return $this->error('Phone number must be in the format \'+2XXYYYYYYYYY\'');
            }
        }

        // Check if currencyCode is set
        if (!isset($parameters['currencyCode'])) {
            return $this->error('currencyCode must be defined');
        } else {
            $currencyCode = $parameters['currencyCode'];
            if (strlen($currencyCode) != 3) {
                return $this->error('currencyCode must be in 3-digit ISO format');
            }
        }

        // Check if amount is set
        if (!isset($parameters['amount'])) {
            return $this->error('amount must be defined');
        }
        $amount = $parameters['amount'];

        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'phoneNumber' => $phoneNumber,
            'currencyCode' => $currencyCode,
            'amount' => $amount,
        ];

        if (!empty($parameters['metadata'])) {
            $requestData['metadata'] = $parameters['metadata'];
        }

        if (!empty($parameters['providerChannel'])) {
            $requestData['providerChannel'] = $parameters['providerChannel'];
        }

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        // Make request data array
        $response = $this->client->post('mobile/checkout/request', $requestOptions);
        return $this->success($response);
    }

    protected function doMobileB2C($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];

        // Check if recipients array is provided
        if (!isset($parameters['recipients'])) {
            return $this->error('recipients must be an array containing, 
            bankAccount, currencyCode, amount, and narration');
        } else if (isset($parameters['recipients']) && is_array($parameters['recipients'])) {
            $recipients = $parameters['recipients'];
            if (count($recipients) > 10) {
                return $this->error('Cannot be more than 10 recipients');
            }
            foreach ($recipients as $r) {
                if (!isset($r['phoneNumber']) || 
                    !isset($r['amount']) || 
                    !isset($r['currencyCode'])) {

                    return $this->error('recipients must be an array containing,
                    phoneNumber, currencyCode, amount');
                }

                if (isset($r['reason'])) {
                    if (!in_array($r['reason'], ['SalaryPayment', 'SalaryPaymentWithWithdrawalChargePaid', 
                    'BusinessPayment', 'BusinessPaymentWithWithdrawalChargePaid', 'PromotionPayment'])) {
                        return $this->error('Reason must be one of SalaryPayment, SalaryPaymentWithWithdrawalChargePaid,
                        BusinessPayment, BusinessPaymentWithWithdrawalChargePaid, PromotionPayment'); 
                    }
                }
            }
        }

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'recipients' => $recipients
        ];

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        $response = $this->client->post('mobile/b2c/request', $requestOptions);
        return $this->success($response);
    }

    protected function doMobileB2B($parameters, $options = [])
    {
        // Check if productName is set
        if (!isset($parameters['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $parameters['productName'];

        // Check if provider is set
        if (!isset($parameters['provider'])) {
            return $this->error('provider must be defined');
        } else if (!in_array($parameters['provider'], ['Athena', 'Mpesa'])) {
            return $this->error('provider must be set as either Athena or Mpesa');
        }
        $provider = $parameters['provider'];
        
        // Check if transferType is set
        if (!isset($parameters['transferType'])) {
            return $this->error('transferType must be defined');
        } else if (!in_array($parameters['transferType'], 
        ['BusinessBuyGoods', 'BusinessPayBill', 'DisburseFundsToBusiness', 'BusinessToBusinessTransfer'])) {
            return $this->error('transferType must be one of BusinessBuyGoods,
            BusinessPayBill, DisburseFundsToBusiness, BusinessToBusinessTransfer');
        }
        $transferType = $parameters['transferType'];

        // Check if currencyCode is set
        if (!isset($parameters['currencyCode'])) {
            return $this->error('currencyCode must be defined');
        } else {
            $currencyCode = $parameters['currencyCode'];
            if (strlen($currencyCode) != 3) {
                return $this->error('currencyCode must be in 3-digit ISO format');
            }
        }

        // Check if amount is set
        if (!isset($parameters['amount'])) {
            return $this->error('amount must be defined');
        }
        $amount = $parameters['amount'];
        
        // Check if destinationChannel is set
        if (!isset($parameters['destinationChannel'])) {
            return $this->error('destinationChannel must be defined');
        }
        $destinationChannel = $parameters['destinationChannel'];

        // Check if destinationAccount is set
        if (!isset($parameters['destinationAccount'])) {
            return $this->error('destinationAccount must be defined');
        }
        $destinationAccount = $parameters['destinationAccount'];

        // Check if metadata is set
        if (!isset($parameters['metadata'])) {
            return $this->error('metadata must be defined');
        }
        $metadata = $parameters['metadata'];

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'provider' => $provider,
            'transferType' => $transferType,
            'currencyCode' => $currencyCode,
            'amount' => $amount,
            'destinationAccount' => $destinationAccount,
            'destinationChannel' => $destinationChannel,
            'metadata' => $metadata
        ];

        $requestOptions = [
            'json' => $requestData,
        ];

        if(isset($options['idempotencyKey'])) {
            $requestOptions['headers'] = [
                'Idempotency-Key' => $options['idempotencyKey'],
            ];
        }

        $response = $this->client->post('mobile/b2b/request', $requestOptions);
        return $this->success($response);
    }

    protected function doMobileData($parameters, $options = [])
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
            'recipients' => $recipients
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

    protected function doWalletTransfer($options)
    {
        // Check if productName is set
        if (!isset($options['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $options['productName'];

        // Check if targetProductCode is set
        if (!isset($options['targetProductCode'])) {
            return $this->error('targetProductCode must be defined');
        }
        $targetProductCode = $options['targetProductCode'];

        // Check if currencyCode is set
        if (!isset($options['currencyCode'])) {
            return $this->error('currencyCode must be defined');
        } else {
            $currencyCode = $options['currencyCode'];
            if (strlen($currencyCode) != 3) {
                return $this->error('currencyCode must be in 3-digit ISO format');
            }
        }
        
        // Check if amount is set
        if (!isset($options['amount'])) {
            return $this->error('amount must be defined');
        }
        $amount = $options['amount'];
        
        // Check if metadata is set
        if (!isset($options['metadata'])) {
            return $this->error('metadata must be defined');
        }
        $metadata = $options['metadata'];

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'targetProductCode' => $targetProductCode,
            'currencyCode' => $currencyCode,
            'amount' => $amount,
            'metadata' => $metadata
        ];

        $response = $this->client->post('transfer/wallet', ['json' => $requestData]);
        return $this->success($response);
    }

    protected function doTopupStash($options)
    {
        // Check if productName is set
        if (!isset($options['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $options['productName'];

        // Check if currencyCode is set
        if (!isset($options['currencyCode'])) {
            return $this->error('currencyCode must be defined');
        } else {
            $currencyCode = $options['currencyCode'];
            if (strlen($currencyCode) != 3) {
                return $this->error('currencyCode must be in 3-digit ISO format');
            }
        }
        
        // Check if amount is set
        if (!isset($options['amount'])) {
            return $this->error('amount must be defined');
        }
        $amount = $options['amount'];
        
        // Check if metadata is set
        if (!isset($options['metadata'])) {
            return $this->error('metadata must be defined');
        }
        $metadata = $options['metadata'];

        // Make request data array
        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'currencyCode' => $currencyCode,
            'amount' => $amount,
            'metadata' => $metadata
        ];

        $response = $this->client->post('topup/stash', ['json' => $requestData]);
        return $this->success($response);
    }

    protected function doFetchProductTransactions($options)
    {
        if (!isset($options['productName'])) {
            return $this->error('productName must be defined');
        }
        $productName = $options['productName'];

        // Check if filters are provided
        if (!isset($options['filters']) && !is_array($options['filters'])) {
            return $this->error('filters must be an array containing at least a pageNumber and count');
        } else if (isset($options['filters']) && is_array($options['filters'])) {
            $filters = $options['filters'];
            if (!isset($filters['pageNumber']) || 
                !isset($filters['count'])) {
                return $this->error('filters must be an array containing at least a pageNumber and count');
            }
        }

        $pageNumber = $filters['pageNumber'];
        $count = $filters['count'];

        $requestData = [
            'username' => $this->username,
            'productName' => $productName,
            'pageNumber' => $pageNumber,
            'count' => $count
        ];

        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $requestData['startDate'] = $filters['startDate'];
            $requestData['endDate'] = $filters['endDate'];
        }

        if (!empty($filters['category'])) {
            $requestData['category'] = $filters['category'];
        }

        if (!empty($filters['provider'])) {
            $requestData['provider'] = $filters['provider'];
        }

        if (!empty($filters['status'])) {
            $requestData['status'] = $filters['status'];
        }

        if (!empty($filters['source'])) {
            $requestData['source'] = $filters['source'];
        }

        if (!empty($filters['destination'])) {
            $requestData['destination'] = $filters['destination'];
        }

        if (!empty($filters['providerChannel'])) {
            $requestData['providerChannel'] = $filters['providerChannel'];
        }

        $response = $this->client->get('query/transaction/fetch', ['query' => $requestData]);
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

    protected function doFetchWalletTransactions($options)
    {
        // Check if filters are provided
        if (!isset($options['filters']) && !is_array($options['filters'])) {
            return $this->error('filters must be an array containing at least a pageNumber and count');
        } else if (isset($options['filters']) && is_array($options['filters'])) {
            $filters = $options['filters'];
            if (!isset($filters['pageNumber']) || 
                !isset($filters['count'])) {
                return $this->error('filters must be an array containing at least a pageNumber and count');
            }
        }

        $pageNumber = $filters['pageNumber'];
        $count = $filters['count'];

        $requestData = [
            'username' => $this->username,
            'pageNumber' => $pageNumber,
            'count' => $count
        ];

        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $requestData['startDate'] = $filters['startDate'];
            $requestData['endDate'] = $filters['endDate'];
        }

        if (!empty($filters['categories'])) {
            $requestData['categories'] = $filters['categories'];
        }

        $response = $this->client->get('query/wallet/fetch', ['query' => $requestData]);
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
