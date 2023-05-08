<?php

require('vendor/autoload.php');

use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;


$response->record(array(
	'method' => 'POST',
	'maxLength' => 10,
	'finishOnKey' => '#'

));


header("content-type: text/xml");
echo $response->asXML();


?>