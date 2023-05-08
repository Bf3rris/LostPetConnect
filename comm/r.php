<?php


require('vendor/autoload.php');
use SignalWire\Rest\Client;

 $client = new Client("21a6a9d2-411f-43ca-aff7-8608e66f40e4", "PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7", array("signalwireSpaceUrl" => "bf3rris.signalwire.com"));

$call = $client->calls("7714159d-1c2e-43fb-b7f4-465f151bab43")
	->fetch();
$status = $call->answeredBy;

print_r($status);

?>