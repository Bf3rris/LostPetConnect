<?php
require('../connection.php');
require('vendor/autoload.php');


use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;

$response = new VoiceResponse;

$from = $_REQUEST['From'];

$search = "SELECT * FROM call_log WHERE from_number = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $from);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $call_id = $array['call_id'];
					 $out_id = $array['out_id'];
					}
				   $stmt->close();



$who = "SELECT * FROM call_log WHERE out_id = ?";
$stmt = $conn->prepare($who);
$stmt->bind_param('s', $out_id,);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $noun = $array['icamd'];
					 
					}

$stmt->close();
				   
  $client = new Client("21a6a9d2-411f-43ca-aff7-8608e66f40e4", "PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7", array("signalwireSpaceUrl" => "bf3rris.signalwire.com"));

$call = $client->calls($out_id)
	->fetch();
$status = $call->status;


$response->say("Please hold while we reach the owner of the pet.");
///check call status
 


//Used to inform caller that the owner didn't answer and disconnecting the call
 switch($status){
		
		
		case "no-answer";
		$call = $client->calls($call_id)
                 ->update(array("url" => "http://13.59.192.46/x/no_answer.php"));
		break;
				
		case "machine";
		$call = $client->calls($call_id)
                 ->update(array("url" => "http://13.59.192.46/x/no_answer.php"));
		break;
		 
		 case "completed";
			 $call = $client->calls($call_id)
                 ->update(array("url" => "http://13.59.192.46/x/no_answer.php"));
	  }



//
$response->pause(array('length' => '10'));
unset($status);

$call = $client->calls($out_id)
	->fetch();
$status = $call->status;

//$response->hangup();
header("content-type: text/xml");
echo $response->asXML();



?>