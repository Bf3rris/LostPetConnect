<?php

require('../connection.php');
require('vendor/autoload.php');


use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;


$client = new Client('21a6a9d2-411f-43ca-aff7-8608e66f40e4', 'PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7', array("signalwireSpaceUrl" => "bf3rris.signalwire.com"));


$var = substr($_REQUEST['To'], 2);
$thisid = $_REQUEST['CallSid'];


 $call = $client->calls($thisid)
                 ->fetch();
							   
                 
 $status = $call->answeredBy;
$other = $call->status;

//Blank var
$blnk = "";

//Updating to clear amd for repeat calls
$clear = "UPDATE call_log SET icamd = ? WHERE out_id = ?";
$stmt = $conn->prepare($clear);
$stmt->bind_param('ss', $blnk, $thisid);
$stmt->execute();
$stmt->close();


//used in datadabase during call to check if call was picke up by robot or human
$update = "UPDATE call_log SET icamd = ? WHERE out_id = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param('ss', $status, $thisid);
$stmt->execute();
$stmt->close();



$response = new VoiceResponse;

			///*******start section to control call if answered by-

if($status == "machine"){
	
	$call = $client->calls($thisid)
                 ->update(array("url" => "http://13.59.192.46/x/no_answer.php"));

}


	
	////**************
$gather = $response->gather(array(
'action' => 'http://13.59.192.46/x/call_action.php',
	'input' => 'dtmf',
	'timeout' => 10,
	'method' => 'POST',
	'numDigits' => 1));

//////


$gather->say('Hello from Lost Pet Connect');
	$gather->say("You are receiving a call from a locator of your pet.");
$gather->say('Press 1 to accept the call and be connected or press 2 to decline the call.');



header("content-type: text/xml");
echo $response->asXML();

?>