<?php

require('vendor/autoload.php');

use \SignalWire\LaML\VoiceResponse;


$response = new VoiceResponse;

$response->say("You have entered the conversation with...");

header("content-type: text/xml");
echo $response->asXML();




?>