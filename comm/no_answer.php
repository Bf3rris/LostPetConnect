<?php

require('vendor/autoload.php');

use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;


$response->say("The owner of the pet could not be reached. Please try again later.");
$response->say("Thank you for using Lost Pet Connect.");
header("content-type: text/xml");
echo $response->asXML();


?>