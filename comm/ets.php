<?php

require('vendor/autoload.php');

use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;

$response = new VoiceResponse;

$sid = $_REQUEST['CallSid'];
$status = $_REQUEST['CallStatus'];

$rand = rand(0,99999);


$data = $_POST;
file_put_contents("instant_data.txt", $data);


header("content-type: text/xml");
echo $response->asXML();

?>