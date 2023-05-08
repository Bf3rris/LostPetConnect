<?php
require_once('vendor/autoload.php');
$PROJECT_ID = "21a6a9d2-411f-43ca-aff7-8608e66f40e4";
	$API_TOKEN = "PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7";
	$SPACE_URL = "bf3rris.signalwire.com";
$TO_NUMBER = "+19374431724";
$FROM_NUMBER = "+19374539293";
  use SignalWire\Rest\Client;
use \SignalWire\LaML\MessageResponse;

$response = new MessageResponse;

$client = new SignalWire\Relay\Client([
  'project' => $PROJECT_ID,
  'token' => $API_TOKEN
]);

$rand = rand(0000,9999);
$msg = "something here..";

$client->on('signalwire.ready', function($client) {

  $params = [ 'type' => 'phone', 'from' => '+19374539293', 'to' => '+19374431724', 'body' => 'insert message' ];
  $client->messaging->send($params)->done(function($sendResult) {
    if ($sendResult->isSuccessful()) {
      // Your active $call.
		$response->message('something else');
		
    
    }
  });

});

$client->connect();




?>