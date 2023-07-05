<?php

//Start mysqli connection
require('../connection.php');


//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path']; 
					 
					 //Var holding SignalWire space url
					 $space_url = $array['space_url'];

					 
					 //Var holding SignalWire project id
					$project_id = $array['project_id'];

					 //Var holding SignalWire auth token
					 $token = $array['token'];

					}
$stmt->close();


//Composers autoload
require($composer_path);


use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;

$response = new VoiceResponse;

//Var containing locators phone number
$from = strip_tags($_POST['From']);

//Var holding incoming call sid
$sid = strip_tags($_POST['CallSid']);


//Selecting call details from call log
$search = "SELECT call_id, out_id FROM call_log WHERE from_number = ? and call_id = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('ss', $from, $sid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding call id from SignalWire
					 $call_id = $array['call_id'];
					 
					 //Var holding outgoing call sid to pet owner
					 $out_id = $array['out_id'];
					}
				   $stmt->close();


//Selecting amd call details from call log
$who = "SELECT icamd FROM call_log WHERE out_id = ?";
$stmt = $conn->prepare($who);
$stmt->bind_param('s', $out_id);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var containing call answer status
					 $noun = $array['icamd'];
					 
					}

$stmt->close();
				   

//Checking status of outgoing call to pet owner
  $client = new Client($project_id, $token, array("signalwireSpaceUrl" => $space_url));

$call = $client->calls($out_id)
	->fetch();

//Status of outgoing call to pet owner
$status = $call->status;

//TTS notification of outgoing call
$response->say("Please hold while we reach the owner of the pet.");



//Var holding active call status
$active = "1";

//update active call to 1
$sql = "UPDATE call_log SET active_call = ? WHERE from_number = ? AND call_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $active, $from, $sid);
$stmt->execute();
$stmt->close();

///check call status
 //Used to inform caller that the owner didn't answer and disconnecting the call
 switch($status){
		
		
		case "no-answer";
		$call = $client->calls($call_id)
                 ->update(array("url" => "$domain/comm/no_answer.php"));
		break;
				
		case "machine";
		$call = $client->calls($call_id)
                 ->update(array("url" => "$domain/comm/no_answer.php"));
		break;
		 
		 case "completed";
			 $call = $client->calls($call_id)
                 ->update(array("url" => "$domain/comm/no_answer.php"));
	  }



//Setting 10-sec pause for status check
$response->pause(array('length' => '10'));
unset($status);

//Fetching status of call
$call = $client->calls($out_id)
	->fetch();
$status = $call->status;

header("content-type: text/xml");
echo $response->asXML();



?>