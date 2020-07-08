<?php
namespace AfricasTalking\SDK\Tests;

use AfricasTalking\SDK\AfricasTalking;
use AfricasTalking\SDK\Payments;
use GuzzleHttp\Exception\GuzzleException;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
	public function setup()
	{
		$this->username = Fixtures::$username;
		$this->apiKey 	= Fixtures::$apiKey;

		$at 			= new AfricasTalking($this->username, $this->apiKey);

		$this->client 	= $at->payments();
    }

    public function testCardCheckoutCharge()
    {
		$response = $this->client->cardCheckoutCharge([
			'productName' => Fixtures::$productName,
			'paymentCard' => Fixtures::$paymentCard,
			'currencyCode' => Fixtures::$currencyCode2,
			'amount' => rand(1000, 8000),
			'narration' => Fixtures::$narration,
			'metadata' => Fixtures::$metadata
		]);

		$this->assertEquals('success', $response['status']);
    }

    public function testCardCheckoutChargeIdempotency()
    {
		$response = $this->client->cardCheckoutCharge([
			'productName' => Fixtures::$productName,
			'paymentCard' => Fixtures::$paymentCard,
			'currencyCode' => Fixtures::$currencyCode2,
			'amount' => rand(1000, 8000),
			'narration' => Fixtures::$narration,
			'metadata' => Fixtures::$metadata
		], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
        ]);

		$this->assertEquals('success', $response['status']);
    }

	public function testCardCheckoutValidate()
	{
		$response = $this->client->cardCheckoutValidate([
			'transactionId' => Fixtures::$transactionId,
			'otp' => Fixtures::$otp
		]);

		$this->assertArrayHasKey('status', $response);
	}

	public function testBankCheckoutCharge()
	{
		$response = $this->client->bankCheckoutCharge([
			'productName' => Fixtures::$productName,
			'bankAccount' => Fixtures::$bankAccount,
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => rand(1000, 2000),
			'narration' => Fixtures::$narration,
			'metadata' => Fixtures::$metadata
		]);

		$this->assertEquals('PendingValidation', $response['data']->status);
	}

    public function testBankCheckoutChargeIdempotency()
	{
		$response = $this->client->bankCheckoutCharge([
			'productName' => Fixtures::$productName,
			'bankAccount' => Fixtures::$bankAccount,
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => rand(1000, 2000),
			'narration' => Fixtures::$narration,
			'metadata' => Fixtures::$metadata
		], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
        ]);

		$this->assertEquals('PendingValidation', $response['data']->status);
	}

	public function testBankCheckoutValidate()
	{
		$response = $this->client->bankCheckoutValidate([
			'transactionId' => Fixtures::$transactionId,
			'otp' => Fixtures::$otp
		]);

		$this->assertArrayHasKey('status', $response);
	}

	public function testBankTransfer()
    {
		$response = $this->client->bankTransfer([
			'productName' => Fixtures::$productName,
			'recipients' => [
				[
					'bankAccount' => Fixtures::$bankAccount,
					'currencyCode' => Fixtures::$currencyCode2,
					'amount' => rand(1000, 5000),
					'narration' => Fixtures::$narration,
					'metadata' => ['notes'=> 'Some transfer metadata here']
				]
			]
		]);

		$this->assertEquals('Queued', $response['data']->entries[0]->status);
	}

    public function testBankTransferIdempotency()
    {
		$response = $this->client->bankTransfer([
			'productName' => Fixtures::$productName,
			'recipients' => [
				[
					'bankAccount' => Fixtures::$bankAccount,
					'currencyCode' => Fixtures::$currencyCode2,
					'amount' => rand(1000, 5000),
					'narration' => Fixtures::$narration,
					'metadata' => ['notes'=> 'Some transfer metadata here']
				]
			]
		], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
        ]);

		$this->assertEquals('Queued', $response['data']->entries[0]->status);
	}

	public function testMobileCheckout()
	{
		$response = $this->client->mobileCheckout([
			'productName' => Fixtures::$productName,
			'phoneNumber' => Fixtures::$phoneNumber,
			'amount' => Fixtures::$amount,
			'currencyCode' => Fixtures::$currencyCode
		], [
			'idempotencyKey' => 'req-' . mt_rand(10, 100),
		]);

		$this->assertEquals('PendingConfirmation', $response['data']->status);
	}

    public function testMobileCheckoutIdempotency()
	{
		$response = $this->client->mobileCheckout([
			'productName' => Fixtures::$productName,
			'phoneNumber' => Fixtures::$phoneNumber,
			'amount' => Fixtures::$amount,
			'currencyCode' => Fixtures::$currencyCode
		], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
		]);

		$this->assertArrayHasKey('status', $response);
	}

	public function testMobileB2C()
	{
		$response = $this->client->mobileB2C([
			'productName' => Fixtures::$productName,
			'recipients' => Fixtures::$B2CRecipients,
		], [
			'idempotencyKey' => 'req-' . mt_rand(10, 100),
		]);

		$this->assertEquals(1, $response['data']->numQueued);
	}

    public function testMobileB2CIdempotency()
	{
		$response = $this->client->mobileB2C([
			'productName' => Fixtures::$productName,
			'recipients' => Fixtures::$B2CRecipients,
		], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
        ]);

		$this->assertArrayHasKey('status', $response);
    }

	public function testMobileB2B()
	{
		$response = $this->client->mobileB2B([
			'productName' => Fixtures::$productName,
			'provider' => Payments::PROVIDER['ATHENA'],
			'transferType' => Payments::TRANSFER_TYPE['B2B_TRANSFER'],
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => Fixtures::$amount,
			'destinationChannel' => Fixtures::$destinationChannel,
			'destinationAccount' => Fixtures::$destinationAccount,
			'metadata' => Fixtures::$metadata
		], [
			'idempotencyKey' => 'req-' . mt_rand(10, 100),
		]);

		$this->assertArrayHasKey('status', $response);
	}

    public function testMobileB2BIdempotency()
	{
		$response = $this->client->mobileB2B([
			'productName' => Fixtures::$productName,
			'provider' => Payments::PROVIDER['ATHENA'],
			'transferType' => Payments::TRANSFER_TYPE['B2B_TRANSFER'],
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => Fixtures::$amount,
			'destinationChannel' => Fixtures::$destinationChannel,
			'destinationAccount' => Fixtures::$destinationAccount,
			'metadata' => Fixtures::$metadata
		], [
            'idempotencyKey' => 'req-' . mt_rand(10, 100),
		]);

		$this->assertArrayHasKey('status', $response);
	}

	public function testMobileData()
	{
        $response = $this->client->mobileData([
            'productName' => Fixtures::$productName,
            'recipients'  => Fixtures::$MobileDataRecipients,
		]);

        $this->assertArrayHasKey('status', $response);
	}

	public function testWalletTransfer()
	{
		$response = $this->client->walletTransfer([
			'productName' => Fixtures::$productName,
			'provider' => Payments::PROVIDER['ATHENA'],
			'targetProductCode' => Fixtures::$targetProductCode,
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => Fixtures::$amount,
			'metadata' => Fixtures::$metadata
		]);

		$this->assertEquals('Success', $response['data']->status);
	}

	public function testTopupStash()
	{
		$response = $this->client->topupStash([
			'productName' => Fixtures::$productName,
			'provider' => Payments::PROVIDER['ATHENA'],
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => Fixtures::$amount,
			'metadata' => Fixtures::$metadata
		]);

		$this->assertEquals('Success', $response['data']->status);
	}

    public function testFetchProductTransactions()
    {
        $response = $this->client->fetchProductTransactions([
            'productName' => Fixtures::$productName,
            'filters' => [
                'pageNumber' => 1,
                'count' => 10,
                'startDate' => Fixtures::$startDate,
                'endDate' => Fixtures::$endDate,
                'category' => Fixtures::$paymentCategory,
                'provider' => Fixtures::$paymentProvider,
                'status' => 'Success',
                'source' => Fixtures::$paymentSource,
                'destination' => Fixtures::$paymentDestination,
                'providerChannel' => Fixtures::$providerChannel
            ]
        ]);

        $this->assertEquals('Success', $response['data']->status);
    }

    public function testFindTransaction()
    {
        $response = $this->client->findTransaction([
            'transactionId' => Fixtures::$transactionId
        ]);

        $this->assertEquals('Failure', $response['data']->status);
    }

    public function testFetchWalletTransactions()
    {
        $response = $this->client->fetchWalletTransactions([
            'productName' => Fixtures::$productName,
            'filters' => [
                'pageNumber' => 1,
                'count' => 10,
                'startDate' => Fixtures::$startDate,
                'endDate' => Fixtures::$endDate,
                'categories' => Fixtures::$paymentCategories
            ]
        ]);

        $this->assertArrayHasKey('status', $response);
    }

    public function testFetchWalletBalance()
    {
        $response = $this->client->fetchWalletBalance();

        $this->assertEquals('Success', $response['data']->status);
    }
}
