<?php

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
					 
					 //Var holding SignalWire phone number
					 $xfn = $array['xfn'];
					 
					 //Var holding SignalWire project id
					$project_id = $array['project_id'];

					 //Var holding SignalWire auth token
					 $token = $array['token'];

					}
$stmt->close();

//Composer autoload path
require($composer_path);

use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;

//Starting client
$client = new Client($project_id, $token, array("signalwireSpaceUrl" => "$space_url"));

//Removing first to characters from number
$var = strip_tags(substr($_POST['To'], 2));

//Var holding call sid of incoming call
$call_sid = strip_tags($_POST['CallSid']);

//Checking status of call to pet owner
 $call = $client->calls($call_sid)
                 ->fetch();
							   
 //Phone call answered by (huan or machine) to pet owner
 $status = $call->answeredBy;


//Intentionally blank var
$blank = "";

//Updating call log to clear AMD data for future calls
$clear = "UPDATE call_log SET icamd = ? WHERE out_id = ?";
$stmt = $conn->prepare($clear);
$stmt->bind_param('ss', $blank, $call_sid);
$stmt->execute();
$stmt->close();


//Used in datadabase during call to check if call was picke up by robot or human
$update = "UPDATE call_log SET icamd = ? WHERE out_id = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param('ss', $status, $call_sid);
$stmt->execute();
$stmt->close();



$response = new VoiceResponse;

			
//If call is answered by machine, locator directed to new xml saying so
//This method prevents uninentional waiting and voicemails from occurring

if($status == "machine"){
	
	//Updating call with 'no answer' XML
	$call = $client->calls($call_sid)
                 ->update(array("url" => "$domain/comm/no_answer.php"));

}


	
//Gather of information
$gather = $response->gather(array(
'action' => $domain.'/comm/call_action.php',
	'input' => 'dtmf',
	'timeout' => 10,
	'method' => 'POST',
	'numDigits' => 1));


//Greeting owner of pet and prompting possible actions
$gather->say('Hello from Lost Pet Connect');
	$gather->say("You are receiving a call from a locator of your pet.");
$gather->say('Press 1 to accept the call and be connected or press 2 or simply hangup to decline the call.');



header("content-type: text/xml");
echo $response->asXML();

?>