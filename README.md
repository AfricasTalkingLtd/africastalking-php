# africastalking-php
Official AfricasTalking PHP API wrapper


# How to Install

```bash
composer require africastalking/africastalking
```


## How to use

### Sending a message

```php
<?php
require_once('AfricasTalkingGateway.php');

// Specify your login credentials
$username   = "MyAfricasTalkingUsername";
$apikey     = "MyAfricasTalkingAPIKey";

// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)
$recipients = "+254711XXXYYY,+254733YYYZZZ";

// And of course we want our recipients to know what we really do
$message    = "I'm a lumberjack and its ok, I sleep all night and I work all day";

// Create a new instance of our awesome gateway class
$gateway    = new AfricasTalkingGateway($username, $apikey);

try 
{ 
  // Thats it, hit send and we'll take care of the rest. 
  $results = $gateway->sendMessage($recipients, $message);
            
  foreach($results as $result) {
    // status is either "Success" or "error message"
    echo " Number: " .$result->number;
    echo " Status: " .$result->status;
    echo " MessageId: " .$result->messageId;
    echo " Cost: "   .$result->cost."\n";
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while sending: ".$e->getMessage();
}
```


### Fetching messages

```php
<?php
// Include the helper gateway class
require_once('AfricasTalkingGateway.php');

// Specify your login credentials
$username   = "MyAfricasTalkingUsername";
$apikey     = "MyAfricasTalkingAPIKey";

// Create a new instance of our awesome gateway class
$gateway  = new AfricaStalkingGateway($username, $apikey);

// Any gateway errors will be captured by our custom Exception class below, 
// so wrap the call in a try-catch block
try 
{
  // Our gateway will return 100 messages at a time back to you, starting with
  // what you currently believe is the lastReceivedId. Specify 0 for the first
  // time you access the gateway, and the ID of the last message we sent you
  // on subsequent results
  $lastReceivedId = 0;

  do {
    
    $results = $gateway->fetchMessages($lastReceivedId);
    foreach($results as $result) {
      
      echo " From: " .$result->from;
      echo " To: " .$result->to;
      echo " Message: ".$result->text;
      echo " Date Sent: " .$result->date;
      echo " LinkId: " .$result->linkId;
      echo " id: ".$result->id;
      echo "\n";
      $lastReceivedId = $result->id;
      
    }
  } while ( count($results) > 0 );
  
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error: ".$e->getMessage();
}
```


### Making a call


```php
<?php
// Be sure to include our gateway class
require_once('AfricasTalkingGateway.php');

// Specify your login credentials
$username   = "MyAfricasTalking_Username";
$apikey     = "MyAfricasTalking_APIKey";

// Specify your Africa's Talking phone number in international format
$from = "+254711082XYZ";

// Specify the numbers that you want to call to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case, +256 for Uganda)
$to   = "+254711XXXYYY,+254733YYYZZZ";

// Create a new instance of our awesome gateway class
$gateway = new AfricasTalkingGateway($username, $apikey);

try 
{
  $results = $gateway->call($from, $to);

  //This will loop through all the numbers you requested to be called
  foreach($results as $result) {
    echo $result->status;
    echo $result->phoneNumber;
    echo "<br/>";
  }
        
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while making the call: ".$e->getMessage();
}
```


### Sending airtime

```php
<?php

    require_once "AfricasTalkingGateway.php";
    
    //Specify your credentials
    $username = "myAfricasTalkingUsername";
    $apiKey   = "myAfricasTalkingAPIKey";

    $recipients = array(
                    array("phoneNumber"=>"+254700XXXYYY", "amount"=>"KES 100"),
                    array("phoneNumber"=>"+254733YYYZZZ", "amount"=>"KES 100")
                    );
    $recipientStringFormat = json_encode($recipients);

    $gateway = new AfricasTalkingGateway($username, $apiKey);
   
   try {
    $results = $gateway->sendAirtime($recipientStringFormat);

    foreach($results as $result) {
     echo $result->status;
     echo $result->amount;
     echo $result->phoneNumber;
     echo $result->discount;
     echo $result->requestId;

     echo $esult->errorMessage;
    }
   }
   catch(AfricasTalkingGatewayException $e){
    echo $e->getMessage();
   }
```


### Getting user data (get balance)


```php
<?php
require_once('AfricasTalkingGateway.php');

$username   = "MyAfricasTalkingUsername";
$apikey     = "MyAfricasTalkingApiKey";

$gateway    = new AfricasTalkingGateway($username, $apikey);

try
{ 
  $data = $gateway->getUserData();
  echo "Balance: " . $data->balance."\n";
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while fetching user data: ".$e->getMessage()."\n";
}
```


### Contributions and Issues

Feel free to contribute and create issues.