<?php

/*

  # COPYRIGHT (C) 2014 AFRICASTALKING LTD <www.africastalking.com>                                                   
 
 AFRICAStALKING SMS GATEWAY CLASS IS A FREE SOFTWARE IE. CAN BE MODIFIED AND/OR REDISTRIBUTED                        
 UNDER THE TERMS OF GNU GENERAL PUBLIC LICENCES AS PUBLISHED BY THE                                                 
 FREE SOFTWARE FOUNDATION VERSION 3 OR ANY LATER VERSION                                                            
 
 THE CLASS IS DISTRIBUTED ON 'AS IS' BASIS WITHOUT ANY WARRANTY, INCLUDING BUT NOT LIMITED TO                       
 THE IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.                     
 IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,            
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE       
 OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 
 */

class AfricasTalkingGatewayException extends Exception{}

class AfricasTalkingGateway
{
  protected $_username;
  protected $_apiKey;
  
  protected $_requestBody;
  protected $_requestUrl;
  
  protected $_responseBody;
  protected $_responseInfo;
  
  const SMS_URL          = 'https://api.africastalking.com/version1/messaging';
  const VOICE_URL        = 'https://voice.africastalking.com';
  const USER_DATA_URL    = 'https://api.africastalking.com/version1/user';
  const SUBSCRIPTION_URL = 'https://api.africastalking.com/version1/subscription';
  const AIRTIME_URL      = 'https://api.africastalking.com/version1/airtime';

  
  //Turn this on if you run into problems. It will print the raw HTTP response from our server
  const Debug            = false;
  
  const HTTP_CODE_OK      = 200;
  const HTTP_CODE_CREATED = 201;
  
  public function __construct($username_, $apiKey_)
  {
    $this->_username    = $username_;
    $this->_apiKey      = $apiKey_;
    
    $this->_requestBody = null;
    $this->_requestUrl  = null;
    
    $this->_responseBody = null;
    $this->_responseInfo = null;    
  }
  
  
  //Messaging methods
  public function sendMessage($to_, $message_, $from_ = null, $bulkSMSMode_ = 1, Array $options_ = array())
  {
    if ( strlen($to_) == 0 || strlen($message_) == 0 ) {
      throw new AfricasTalkingGatewayException('Please supply both to and message parameters');
    }
    
    $params = array(
		    'username' => $this->_username,
		    'to'       => $to_,
		    'message'  => $message_,
		    );
    
    if ( $from_ !== null ) {
      $params['from']        = $from_;
      $params['bulkSMSMode'] = $bulkSMSMode_;
    }
    
    //This contains a list of parameters that can be passed in $options_ parameter
    if ( count($options_) > 0 ) {
      $allowedKeys = array (
			    'enqueue',
			    'keyword',
			    'linkId',
			    'retryDurationInHours'
			    );
			    
			    //Check whether data has been passed in options_ parameter
      foreach ( $options_ as $key => $value ) {
							if ( in_array($key, $allowedKeys) && strlen($value) > 0 ) {
	  					$params[$key] = $value;
							} else {
	  					throw new AfricasTalkingGatewayException("Invalid key in options array: [$key]");
							}
      }
    }
    
    $this->_requestUrl  = self::SMS_URL;
    $this->_requestBody = http_build_query($params, '', '&');
    
    $this->executePOST();
    
    if ( $this->_responseInfo['http_code'] == self::HTTP_CODE_CREATED ) {
    		$responseObject = json_decode($this->_responseBody);
    		return $responseObject->SMSMessageData->Recipients;
    }
    
    throw new AfricasTalkingGatewayException($this->_responseBody);
  }
  

  public function fetchMessages($lastReceivedId_)
  {
    $username = $this->_username;
    $this->_requestUrl = self::SMS_URL.'?username='.$username.'&lastReceivedId='. intval($lastReceivedId_);
    
    $this->executeGet();
         
    if ( $this->_responseInfo['http_code'] == self::HTTP_CODE_OK ) {
      $responseObject = json_decode($this->_responseBody);
      return $responseObject->SMSMessageData->Messages;
    }
    
    throw new AfricasTalkingGatewayException($this->_responseBody);    
  }
  
  
  //Subscription methods
  public function createSubscription($phoneNumber_, $shortCode_, $keyword_)
  {
  	
  	if ( strlen($phoneNumber_) == 0 || strlen($shortCode_) == 0 || strlen($keyword_) == 0 ) {
      throw new AfricasTalkingGatewayException('Please supply phoneNumber, shortCode and keyword');
    }
    
    $params = array(
		    'username'    => $this->_username,
		    'phoneNumber' => $phoneNumber_,
		    'shortCode'   => $shortCode_,
		    'keyword'     => $keyword_
		    );
    
    $this->_requestUrl  = self::SUBSCRIPTION_URL."/create";
    $this->_requestBody = http_build_query($params, '', '&');
    
    $this->executePOST();
    
     if ( $this->_responseInfo['http_code'] != self::HTTP_CODE_CREATED )
     	throw new AfricasTalkingGatewayException($this->_responseBody);
     
    		return json_decode($this->_responseBody);
  }

  public function deleteSubscription($phoneNumber_, $shortCode_, $keyword_)
  {
    if ( strlen($phoneNumber_) == 0 || strlen($shortCode_) == 0 || strlen($keyword_) == 0 ) {
      throw new AfricasTalkingGatewayException('Please supply phoneNumber, shortCode and keyword');
    }
    
    $params = array(
		    'username'    => $this->_username,
		    'phoneNumber' => $phoneNumber_,
		    'shortCode'   => $shortCode_,
		    'keyword'     => $keyword_
		    );
    
    $this->_requestUrl  = self::SUBSCRIPTION_URL."/delete";
    $this->_requestBody = http_build_query($params, '', '&');
    
    $this->executePOST();
    
    if ( $this->_responseInfo['http_code'] != self::HTTP_CODE_CREATED )
    	throw new AfricasTalkingGatewayException($this->_responseBody);
     
    	return json_decode($this->_responseBody);
     
  }
  
  public function fetchPremiumSubscriptions($shortCode_, $keyword_, $lastReceivedId_ = 0)
  {
    $username = $this->_username;
    $this->_requestUrl  = self::SUBSCRIPTION_URL.'?username='.$username.'&shortCode='.$shortCode_;
    $this->_requestUrl .= '&keyword='.$keyword_.'&lastReceivedId='.intval($lastReceivedId_);
    
    $this->executeGet();
        
    if ( $this->_responseInfo['http_code'] == self::HTTP_CODE_OK ) {
    	 $responseObject = json_decode($this->_responseBody);
      return $responseObject->responses;
    }
    
    throw new AfricasTalkingGatewayException($this->_responseBody);
  }
  
  
  //Call methods
  public function call($from_, $to_)
  {
    if ( strlen($from_) == 0 || strlen($to_) == 0 ) {
      throw new AfricasTalkingGatewayException('Please supply both from and to parameters');
    }
    
    $params = array(
		    'username' => $this->_username,
		    'from'     => $from_,
		    'to'       => $to_
		    );
    
    $this->_requestUrl  = self::VOICE_URL . "/call";
    $this->_requestBody = http_build_query($params, '', '&');
    
    $this->executePOST();
     
    if(($responseObject = json_decode($this->_responseBody)) !== null) {
  	 	if($responseObject->Status != "Success")
  		 	throw new AfricasTalkingGatewayException($responseObject->ErrorMessage);
  		}
  		else
  		  throw new AfricasTalkingGatewayException($this->_responseBody);
  }
  
  public function getNumQueuedCalls($phoneNumber_, $queueName = null) 
  {  	
  	$this->_requestUrl = self::VOICE_URL . "/queueStatus";
  	$params = array(
  	      "username"     => $this->_username, 
  	      "phoneNumbers" => $phoneNumber_
  	     );
  	if($queueName !== null)
  		$params['queueName'] = $queueName;
  	$this->_requestBody   = http_build_query($params, '', '&');
  	$this->executePOST();
  	
  	if(($responseObject = json_decode($this->_responseBody)) !== null) {
  	 	if($responseObject->Status == "Success")
  		 	return $responseObject->NumQueued;
  			throw new AfricasTalkingGatewayException($responseObject->ErrorMessage);
  		}
  		
  	throw new AfricasTalkingGatewayException($this->_responseBody);
  }

		
  public function uploadMediaFile($url_) 
  {
  	$params = array(
  	                "username" => $this->_username, 
  	                "url"      => $url_
  	             );
  	             
  	$this->_requestBody = http_build_query($params, '', '&');
  	$this->_requestUrl  = self::VOICE_URL . "/mediaUpload";
  	
  	$this->executePOST();
  	
  	if(($responseObject = json_decode($this->_responseBody)) !== null) {
  	 	if($responseObject->Status != "Success")
  		 	throw new AfricasTalkingGatewayException($responseObject->ErrorMessage);
  		}
  		else
  		  throw new AfricasTalkingGatewayException($this->_responseBody);
  }
  
  
  //Airtime method
  public function sendAirtime($recipients) 
  {
  	$params = array(
  	    "username"    => $this->_username, 
  	    "recipients"  => $recipients
  	   );
  	$this->_requestUrl  = self::AIRTIME_URL . "/send";
  	$this->_requestBody = http_build_query($params, '', '&');
  	
  	$this->executePOST();
  	
  	if($this->_responseInfo['http_code'] == self::HTTP_CODE_CREATED) {
  		$responseObject = json_decode($this->_responseBody);
  		if(count($responseObject->responses) > 0)
  			return $responseObject->responses;
  			
  		throw new AfricasTalkingGatewayException($responseObject->errorMessage);
  	}
  	
  	throw new AfricasTalkingGatewayException($this->_responseBody);
  }

  //User info method
  public function getUserData()
  {
    $username = $this->_username;
    $this->_requestUrl = self::USER_DATA_URL.'?username='.$username;
    $this->executeGet();
    
    if ( $this->_responseInfo['http_code'] == self::HTTP_CODE_OK ) {
    	 $responseObject = json_decode($this->_responseBody);
    	 return $responseObject->UserData;
    	}
    	
     throw new AfricasTalkingGatewayException($this->_responseBody);
  }
  
  private function executeGet ()
  {
  	 $ch = curl_init();
    $this->doExecute($ch);
  }
  
  private function executePost ()
  {
  	 $ch = curl_init();
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_requestBody);
    curl_setopt($ch, CURLOPT_POST, 1);
    $this->doExecute($ch);
  }
  
  private function doExecute (&$curlHandle_)
  {
	   try {
	   	
			    $this->setCurlOpts($curlHandle_);
			    $responseBody = curl_exec($curlHandle_);
			    
			    if ( self::Debug ) {
			      echo "Full response: ". print_r($responseBody, true)."\n";
			    }
			    
			    $this->_responseInfo = curl_getinfo($curlHandle_);
			    
			    $this->_responseBody = $responseBody;
			    curl_close($curlHandle_);
	   }
	   
	   catch(Exeption $e) {
	    curl_close($curlHandle_);
	    throw $e;
	   }
  }
  
  private function setCurlOpts (&$curlHandle_)
  {
    curl_setopt($curlHandle_, CURLOPT_TIMEOUT, 60);
    curl_setopt($curlHandle_, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curlHandle_, CURLOPT_URL, $this->_requestUrl);
    curl_setopt($curlHandle_, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandle_, CURLOPT_HTTPHEADER, array ('Accept: application/json',
							 'apikey: ' . $this->_apiKey));
  }
}
