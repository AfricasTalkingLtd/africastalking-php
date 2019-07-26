<?php
namespace AfricasTalking\SDK\Tests;

class Fixtures
{
    public static $username = 'sandbox';
    public static $apiKey = '';
    public static $dateOfBirth = '';
    public static $accountName = 'Test Bank Account';
    public static $accountNumber = '0123456789';
    public static $phoneNumber = '+254724486439';
    public static $multiplePhoneNumbersSMS = ['+254724486439', '+254724567567', '+254724567569'];
    public static $voicePhoneNumber = '+254711082489';
    public static $voicePhoneNumber2 = '+254724486439';
    public static $narration = 'Test Payment';
    public static $shortCode = '';
    public static $keyword = 'Test';
    public static $alphanumeric = 'Test';
    public static $targetProductCode = 1411;
    public static $mediaUrl = 'http://thesoundeffect.com/music/mp3/AintTooProudToBeg.mp3';
    public static $amount = '60';
    public static $currencyCode = 'KES';
    public static $currencyCode2 = 'NGN';
    public static $productName = 'Test';
    public static $provider = 'ATHENA';
    public static $transferType = '';
    public static $startDate = '2018-01-01';
    public static $endDate = '2018-12-30';
    public static $destinationChannel = '456789';
    public static $destinationAccount = 'pweza';
    public static $providerChannel = '456789';
    public static $paymentProvider = 'Athena';
    public static $paymentCategory = 'MobileB2C';
    public static $paymentCategories = 'Debit,Credit';
    public static $paymentSource = 'Wallet';
    public static $paymentDestination = 'PhoneNumber';
    public static $paymentCard = [
        'number' => '4111111111111111',
        'cvvNumber' => 654,
        'expiryMonth' => 7,
        'expiryYear' => 2019,
        'authToken' => '2345',
        'countryCode' => 'NG',
    ];
    public static $bankCode = '';
    public static $bankAccount = [
        'accountName' => 'Test Bank Account',
        'accountNumber' => '1234567890',
        'bankCode' => 234001,
        'dateOfBirth' => '',
    ];
    public static $metadata = ['name' => 'Lip Gallagher'];
    public static $transactionId = 'ATPid_SampleTxnId1';
    public static $otp = '1234';
    public static $bankCheckoutToken = '';
    public static $B2BRecipients = array([
        'name' => '',
        'phoneNumber' => '',
        'amount' => '',
        'providerChannel' => '',
        'reason' => ''
    ]);

    public static $B2CRecipients = array([
        'name' => 'PB',
        'phoneNumber' => '254724486449',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ]);

    public static $MobileDataRecipients = array([
        'phoneNumber' => '254718769882',
        'quatity' => 7,
        'unit' => 'MB',
        'validity' => 'Daily',
        'metadata' => ['notes' => 'Data for January 2018']
    ]);
}
