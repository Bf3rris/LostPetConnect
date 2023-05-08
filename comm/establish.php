<?php
require('vendor/autoload.php');

use \SignalWire\LaML\VoiceResponse;

$response = new SignalWire\LaML\VoiceResponse;


header("content-type: text/xml");
$response->say("the owner of the pet has answered and you are now being connected.");
echo $response->asXML();

?>