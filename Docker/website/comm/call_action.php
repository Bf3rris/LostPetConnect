<?php

//Start mysqli connection
require('../connection.php');


//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT domain, composer_path FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path']; 	 

					}
$stmt->close();

	//Composers autoload path
	require($composer_path);

  use \SignalWire\LaML\VoiceResponse;

	$response = new VoiceResponse;
	
	//Pet owners phone number
	$to = strip_tags(substr($_POST['To'], 2));



	//Using pet owners phone number to tranlate to uid
	$select_number = "SELECT uid FROM users WHERE phone_number = ?";
	$stmt = $conn->prepare($select_number);
	$stmt->bind_param('s', $to);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 
						 //Var holding uid of pet owner
						 $uid = $array['uid'];
						}
	$stmt->close();


	//Digits received from call_action.php (1/accept or 2/decline)
	$digits = strip_tags($_POST['Digits']);

if($digits == "1"){
	
	

	//active call status
	$acs = "1";
	
	//Selecting que name/id from call log where active caller is waiting
	$select_log = "SELECT tag FROM call_log WHERE uid = ? AND active_call = ?";
	$stmt = $conn->prepare($select_log);
	$stmt->bind_param('ss', $uid, $acs);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 
						 //Var holding name/id of que for pet owner to answer
						 $tag = $array['tag'];
						}
	$stmt->close();
	
	
	
	//Owner answers current call que
	$dial = $response->dial();
	$dial->queue($tag, ['url' => $domain.'/comm/establish.php']);
	
	

	
	
	
}else{
	//TTS played back to pet owner if they decline call from pet locator
	$response->say("You have declined to answer the call. Good bye.");
	

	
	//Selecting call id and que name from call log
	$select_log = "SELECT call_id, tag FROM call_log WHERE uid = ? AND active_call = ?";
	$stmt = $conn->prepare($select_log);
	$stmt->bind_param('ss', $uid, $acs);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						
						 
							//Var holding call id					 
						 $call_id = $array['call_id'];
						}
	$stmt->close();
	
	
	//If pet owner doesn't answer the locators call is updated with new XML informing of no contact made
	$call = $client->calls($call_id)
                 ->update(array("url" => "$domain/comm/no_answer.php"));
	
	$response->hangup();
	
}



header("content-type: text/xml");
echo $response->asXML();


?>