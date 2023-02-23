<?php

//Starting mysqli connection
require('../connection.php');

//Composers autoloader
 require 'vendor/autoload.php';

  use \SignalWire\LaML\VoiceResponse;
  $response = new VoiceResponse;

//Gathering PIN that is generated on pet details page / aides in routing call to correct pet owner
$gather = $response->gather(array(
'action' => 'http://13.59.192.46/x/call_result.php',
	'input' => 'dtmf',
	'timeout' => 4,
	'method' => 'POST',
	'numDigits' => 6));

//Greeting and providing directions to locator of pet
$gather->say('Welcome to Lost Pet Connect. Please enter the 6-digit PIN.');
$response->say('Please enter the pets 4 digit code');

header("Content-type: text/xml");
echo $response->asXML();



?>