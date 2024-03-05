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
    public static $productName = 'TestProduct';
    public static $provider = 'ATHENA';
    public static $transferType = '';
    public static $startDate = '2018-01-01';
    public static $endDate = '2018-12-30';
    public static $transactionId = 'ATPid_SampleTxnId1';
    public static $otp = '1234';
    
    public static $MobileDataRecipients = array([
        'phoneNumber' => '25471xxxxxxx',
        'quantity' => 15,
        'unit' => 'MB',
        'validity' => 'Day',
        'metadata' => ['notes' => 'Data for January 2018']
    ]);
}
