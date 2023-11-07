# Africa's Talking PHP SDK

[![Latest Stable Version](https://img.shields.io/packagist/v/africastalking/africastalking)](https://packagist.org/packages/africastalking/africastalking)

> This SDK provides convenient access to the Africa's Talking API for applications written in PHP.

## Documentation

Take a look at the [API docs here](https://developers.africastalking.com).

## Install

You can install the PHP SDK via composer or by downloading the source

#### Via Composer

The recommended way to install the SDK is with [Composer](http://getcomposer.org/).

```bash
composer require africastalking/africastalking
```

## Usage

The SDK needs to be instantiated using your username and API key, which you can get from the [dashboard](https://account.africastalking.com).

> You can use this SDK for either production or sandbox apps. For sandbox, the app username is **ALWAYS** `sandbox`

```php
use AfricasTalking\SDK\AfricasTalking;

$username = 'YOUR_USERNAME'; // use 'sandbox' for development in the test environment
$apiKey   = 'YOUR_API_KEY'; // use your sandbox app API key for development in the test environment
$AT       = new AfricasTalking($username, $apiKey);

// Get one of the services
$sms      = $AT->sms();

// Use the service
$result   = $sms->send([
    'to'      => '+2XXYYYOOO',
    'message' => 'Hello World!'
]);

print_r($result);
```

See [example](example/) for more usage examples.

## Instantiation

Instantiating the class will give you an object with available methods

- `$AT = new AfricasTalking($username, $apiKey)`: Instantiate the class
- Get available service
  - [SMS Service](#sms): `$sms = $AT->sms()`
  - [Content Service](#content): `$content = $AT->content()`
  - [Airtime Service](#airtime): `$airtime = $AT->airtime()`
  - [Mobile Data Service](#mobiledata): `$mobileData = $AT->mobileData()`
  - [Voice Service](#voice): `$voice = $AT->voice()`
  - [Token Service](#token): `$token = $AT->token()`
  - [Application Service](#application): `$application = $AT->application()`

### Application

- `fetchApplicationData()`: Get app information. e.g balance

### Airtime

- `send($parameters, $options)`: Send airtime

  - **$parameters:** associative array with the following keys:

    - `recipients`: An array of arrays containing the following keys
      - `phoneNumber`: Recipient of airtime. `REQUIRED`
      - `currencyCode`: 3-digit ISO format currency code (e.g `KES`, `USD`, `UGX` etc). `REQUIRED`
      - `amount`: Amount to send. `REQUIRED`
  - **$options:** optional associative array with the following keys:

    - `idempotencyKey`: Key to use when making idempotent requests
    - `maxNumRetry`: Maximum number of retries in case of failed airtime deliveries due to telco unavailability or any other reason.

### SMS

- `send($options)`: Send a message

  - `message`: SMS content. `REQUIRED`
  - `to`: An array of phone numbers. `REQUIRED`
  - `from`: Shortcode or alphanumeric ID that is registered with your Africa's Talking account.
  - `enqueue`: Set to `true` if you would like to deliver as many messages to the API without waiting for an acknowledgement from telcos.
- `fetchMessages($options)`: Fetch your messages

  - `lastReceivedId`: This is the id of the message you last processed. Defaults to `0`

***The followoing methods have been moved to the content service, but, have been maintained on SMS for backwards compatibility:***

- `sendPremium($options)`: Send a premium SMS. Calls `$content->send($options)`
- `createSubscription($options)`: Create a premium subscription. Calls `$content->createSubscription($options)`
- `fetchSubscriptions($options)`: Fetch your premium subscription data. Calls `$content->fetchSubscriptions($options)`
- `deleteSubscription($options)`: Delete a phone number from a premium subscription. Calls `$content->$deleteSubscription($options)`

### Content

- `send($options)`: Send a premium SMS

  - `message`: SMS content. `REQUIRED`
  - `to`: An array of phone numbers. `REQUIRED`
  - `from`: Shortcode that is registered with your Africa's Talking account. `REQUIRED`
  - `keyword`: Your premium product keyword
  - `linkId`: "[...] We forward the `linkId` to your application when a user sends a message to your onDemand service"
  - `retryDurationInHours`: "This specifies the number of hours your subscription message should be retried in case it's not delivered to the subscriber"
- `createSubscription($options)`: Create a premium subscription

  - `shortCode`: Premium short code mapped to your account. `REQUIRED`
  - `keyword`: Premium keyword under the above short code and is also mapped to your account. `REQUIRED`
  - `phoneNumber`: PhoneNumber to be subscribed `REQUIRED`
- `fetchSubscriptions($options)`: Fetch your premium subscription data

  - `shortCode`: Premium short code mapped to your account. `REQUIRED`
  - `keyword`: Premium keyword under the above short code and mapped to your account. `REQUIRED`
  - `lastReceivedId`: ID of the subscription you believe to be your last. Defaults to `0`
- `deleteSubscription($options)`: Delete a phone number from a premium subscription

  - `shortCode`: Premium short code mapped to your account. `REQUIRED`
  - `keyword`: Premium keyword under the above short code and is also mapped to your account. `REQUIRED`
  - `phoneNumber`: PhoneNumber to be subscribed `REQUIRED`

### Mobile Data

- `send($parameters, $options)`: Send mobile data to customers

  - **$parameters:** associative array with the following keys:

    - `productName`: Payment product on Africa's Talking. `REQUIRED`
    - `recipients`: A list of recipients. Each recipient has:

      - `phoneNumber`: Customer phone number (in international format). `REQUIRED`
      - `quantity`: Mobile data amount. `REQUIRED`
      - `unit`: Mobile data unit. Can either be `MB` or `GB`. `REQUIRED`
      - `validity`: How long the mobile data is valid for. Must be one of `Day`, `Week` and `Month`. `REQUIRED`
      - `metadata`: Additional data to associate with the tranasction. `REQUIRED`

    - `isPromoBundle`: This is an optional field that can be either `true` or `false`.
    
  - **$options:** optional associative array with the following keys:

    - `idempotencyKey`: Key to use when making idempotent requests
  
- `findTransaction($parameters)`: Find a particular transaction

  - `transactionId`: ID of trancation to find. `REQUIRED`

- `fetchWalletBalance()`: Fetch your payment wallet balance

### Voice

- `call($options)`: Initiate a phone call

  - `to`: Phone number that you wish to dial (in international format). `REQUIRED`
  - `from`: Phone number on Africa's Talking (in international format). `REQUIRED`
  - `clientRequestId`: Variable sent to your Events Callback URL that can be used to tag the call. `OPTIONAL`
- `fetchQueuedCalls($options)`: Fetch queued calls on a phone number

  - `phoneNumber`: Phone number mapped to your Africa's Talking account (in international format). `REQUIRED`
  - `name`: Fetch calls for a specific queue.
- `uploadMediaFile($options)`: Upload a voice media file

  - `phoneNumber`: phone number mapped to your Africa's Talking account (in international format). `REQUIRED`
  - `url`: The url of the file to upload. Should start with `http(s)://`. `REQUIRED`

#### MessageBuilder

Build voice xml when callback URL receives a POST from the voice API. Actions can be chained to create an XML string.

```php
$voiceActions = $voice->messageBuilder();
$xmlresponse = $voiceActions
    ->getDigits($options)
    ->say($text)
    ->record()
    ->build();
```

- `say($text)`: Add a `Say` action
- `text`: Text (in English) that will be read out to the user.
- `play($url)`: Add a `Play` action

  - `url`: Public url to an audio file. This file will be played back to user.
- `getDigits($options)`: Add a `GetDigits` action

  - `numDigits`: Number of digits should be gotten from the user
  - `timeout`: Timeout (in seconds) for getting digits from a user.
  - `finishOnKey`: key which will terminate the action of getting digits.
  - `callbackUrl`: URL to forward the results of the GetDigits action.
- `dial($options)`: Add a `Dial` action

  - `phoneNumbers`: An array of phone numbers (in international format) to call. `REQUIRED`
  - `record`: Boolean - Whether to record the conversation.
  - `sequenntial`: Boolean - If many numbers provided for `phoneNumbers`, determines whether the phone numbers will be dialed one after the other or at the same time.
  - `callerId`: Africa's Talking number you want to dial out with.
  - `ringBackTone`: URL location of a media playback you would want the user to listen to when the call has been placed before its picked up.
  - `maxDuration`: maximum amount of time in seconds a call should take.
- `conference()`: Add a `Conference` action
- `record($options)`: Add a `Record` action

  - `finishOnKey`: Key which will terminate the action of recording.
  - `maxLength`: Maximum amount of time in seconds a recording should take.
  - `timeout`: Timeout (in seconds) for getting a recording from a user.
  - `trimSilence`: Boolean - Specifies whether you want to remove the initial and final parts of a recording where user was silent.
  - `playBeep`: Boolean - Specifies whether the API should play a beep when recording starts.
  - `callbackUrl`: URL to forward the results of the Recording action.
- `enqueue($options)`: Add an `Enqueue` action

  - `holdMusic`: URL to the file to be played while the user is on hold.
  - `name`: Name of queue to put call on.
- `deqeue($options)`: Add a `Dequeue` acton

  - `phoneNumber`: Phone number mapped to your Africa's Talking account which a user called to join the queue. `REQUIRED`
  - `name`: Name of queue you want to dequeue from.
- `reject()`: Add a `Reject` action
- `redirect($url)`: Add a `Redirect` action

  - `url`: URL to transfer control of the call to
- `build()`: Build the xml after chaining some of the above actions

### Token

- `generateAuthToken()`: Generate an auth token to use for authenticating API requests instead of your API key.

## Testing the SDK

The SDK uses [PHPUnit](https://phpunit.de/manual/current/en/index.html) as the test runner.

To run available tests, from the root of the project run:

```bash
# Configure needed fixtures, e.g sandbox api key, Africa's Talking products
cp tests/Fixtures.php.tpl tests/Fixtures.php

# Run tests
phpunit --testdox
```

## Issues

If you find a bug, please file an issue on [our issue tracker on GitHub](https://github.com/AfricasTalkingLtd/africastalking-php/issues).
