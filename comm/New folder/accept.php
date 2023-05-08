<?php
require('vendor/autoload.php');

use Signalwire\LaML;
$response = Signalwire\LaML;

$gather = $response->gather(array(
'action' => 'http://13.59.192.46/x/accept.php',
	'input' => 'dtmf',
	'timeout' => 4,
	'method' => 'POST',
	'numDigits' => 1));
$gather->say("You are receiving a call from a locator of your pet. Press 1 to get instantly connected with them")
echo $response->$asXML();
?>