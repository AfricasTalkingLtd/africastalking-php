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
    
    public function testCardCheckout()
    {
		$response = $this->client->cardCheckout([
			'productName' => Fixtures::$productName,
			'paymentCard' => Fixtures::$paymentCard,
			'currencyCode' => Fixtures::$currencyCode2,
			'amount' => rand(1000, 8000),
			'narration' => Fixtures::$narration,
			'metadata' => Fixtures::$metadata
		]);

		$this->assertEquals('success', $response['status']);
    }

    public function testCardCheckoutCannotBeEmpty()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->cardCheckout()
		);
    }

    public function testCardCheckoutMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->cardCheckout([
				'productName' => Fixtures::$productName
			])
		);
	}
		
	public function testValidateCardCheckout()
	{
		$response = $this->client->validateCardCheckout([
			'transactionId' => Fixtures::$transactionId,
			'otp' => Fixtures::$otp
		]);

		$this->assertArrayHasKey('status', $response);
	}
	
	public function testValidateCardCheckoutCannotBeEmpty()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->validateCardCheckout()
		);
	}
	
	public function testValidateCardCheckoutMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->validateCardCheckout([
				'otp' => Fixtures::$otp
			])
		);
    }

	public function testBankCheckout()
	{
		$response = $this->client->bankCheckout([
			'productName' => Fixtures::$productName,
			'bankAccount' => Fixtures::$bankAccount,
			'currencyCode' => Fixtures::$currencyCode,
			'amount' => rand(1000, 2000),
			'narration' => Fixtures::$narration,
			'metadata' => Fixtures::$metadata
		]);

		$this->assertEquals('PendingValidation', $response['data']->status);
	}
	
	public function testBankCheckoutCannotBeEmpty()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->bankCheckout()
		);
	}
	
	public function testBankCheckoutMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->cardCheckout([
				'productName' => Fixtures::$productName
			])
		);
    }

	public function testValidateBankCheckout()
	{
		$response = $this->client->validateBankCheckout([
			'transactionId' => Fixtures::$transactionId,
			'otp' => Fixtures::$bankCheckoutToken
		]);

		$this->assertArrayHasKey('status', $response);
	}
	
	public function testValidateBankCheckoutCannotBeEmpty()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->validateBankCheckout()
		);
	}
	
	public function testValidateBankCheckoutMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->validateBankCheckout([
				'otp' => Fixtures::$bankCheckoutToken
			])
		);
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

	public function testBankTransferMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->bankTransfer([
				'productName' => Fixtures::$productName,
			])
		);
	}

	public function testMobileCheckout()
	{
		$response = $this->client->mobileCheckout([
			'productName' => Fixtures::$productName,
			'phoneNumber' => Fixtures::$phoneNumber,
			'amount' => Fixtures::$amount,
			'currencyCode' => Fixtures::$currencyCode
		]);

		$this->assertEquals('PendingConfirmation', $response['data']->status);
	}


	public function testMobileCheckoutMustHaveRequiredAttributes()
    {
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->mobileCheckout([
				'productName' => Fixtures::$productName,
				'currencyCode' => Fixtures::$currencyCode,
			])
		);
	}

	public function testMobileB2C()
	{
		$response = $this->client->mobileB2C([
			'productName' => Fixtures::$productName,
			'recipients' => Fixtures::$B2CRecipients,
		]);
		
		$this->assertEquals(1, $response['data']->numQueued);
	}

	public function testMobileB2CMustHaveRequiredAttributes()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->mobileB2C([
				'productName' => Fixtures::$productName,
			])
		);
	}

	public function testMobileB2CRecipientsMustBeLimitedTo10()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->mobileB2C([
				'productName' => Fixtures::$productName,
				'recipients' => Fixtures::$ElevenB2CRecipients,
			])
		);
	}

	public function testMobileB2CCannotBeEmpty()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->mobileB2C()
		);
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
		]);

		$this->assertArrayHasKey('status', $response);
	}

	public function testMobileB2BCannotBeEmpty()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->mobileB2B()
		);
	}

	public function testMobileB2BMustHaveRequiredAttributes()
	{
		$this->assertArraySubset(
			['status' 		=> 'error'],
			$response = $this->client->mobileB2B([
				'productName' => Fixtures::$productName,
				'provider' => Fixtures::$provider,
				'transferType' => Fixtures::$transferType,
			])
		);
	}

}