<?php

require('vendor/autoload.php');

use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;

$response->dial("+18007233228");

header("content-type: text/xml");
echo $response->asXML();



?>