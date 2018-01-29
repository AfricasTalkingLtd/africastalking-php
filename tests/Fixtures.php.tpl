<?php
namespace AfricasTalkingTest;

class Fixtures
{
    public static $username = 'sandbox';
    public static $apiKey = '';
    public static $dateOfBirth = '';
    public static $accountName = 'Test Bank Account';
    public static $accountNumber = '';
    public static $phoneNumber = '+254724486439';
    public static $multiplePhoneNumbersSMS = ['+254724486439', '+254724567567', '+254724567569'];
    public static $voicePhoneNumber = '+254724486439';
    public static $voicePhoneNumber2 = '+254724486439';
    public static $narration = 'Test Payment';
    public static $shortCode = '99999';
    public static $mediaUrl = 'http://thesoundeffect.com/music/mp3/AintTooProudToBeg.mp3';
    public static $amount = '60';
    public static $airtimeAmount = 'KES 60';
    public static $currencyCode = 'KES';
    public static $currencyCode2 = 'NGN';
    public static $productName = 'TestProduct';
    public static $provider = 'ATHENA';
    public static $transferType = '';
    public static $destinationChannel = '456789';
    public static $destinationAccount = 'pweza';
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
        'dateOfBirth' => ''
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
    
    public static $ElevenB2CRecipients = array([
        'name' => 'PB',
        'phoneNumber' => '254724486449',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB1',
        'phoneNumber' => '254724486440',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB2',
        'phoneNumber' => '254724486441',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB3',
        'phoneNumber' => '254723486549',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB4',
        'phoneNumber' => '254723486449',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB5',
        'phoneNumber' => '254724486454',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB6',
        'phoneNumber' => '254724486446',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB7',
        'phoneNumber' => '254724486400',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB8',
        'phoneNumber' => '254724486408',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB9',
        'phoneNumber' => '254724486409',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    [
        'name' => 'PB10',
        'phoneNumber' => '254724486410',
        'amount' => '60',
        'currencyCode' => 'KES',
        'reason' => 'SalaryPayment',
        'metadata' => ['notes' => 'Salary for January 2018']
    ],
    );
}