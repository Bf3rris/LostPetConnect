<?php


//Starting MySQL connection
require('../connection.php');


//Site setting ID
$configid = "1";

//Query for site settings
$select_dn = "SELECT domain, composer_path FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($select_dn);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
				   $array = $result->fetch_assoc();
					$domain = $array['domain'];
					 $composer_path = $array['composer_path'];
				   }
$stmt->close();



//Composer autoloader
require_once ($composer_path);


use \SignalWire\LaML\VoiceResponse;
$response = new VoiceResponse;


$from = substr($_REQUEST['From'], 2);


$digits = $_REQUEST['Digits'];


if($digits == "2"){
	
							 $gather = $response->gather(array(
'action' => $domain.'/x/connect_to.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 6));

//Greeting and providing directions to locator of pet
					
$gather->say('Enter the call pin for the pet that youve located');
	header("content-type: text/xml");
echo $response->asXML();
	
	
	
}elseif($digits == "1"){$response->say("you've pressed 1");
						
//get uid
						$fuid = "SELECT uid FROM users WHERE phone_number = ?";
						$stmt = $conn->prepare($fuid);
						$stmt->bind_param('s', $from);
						if($stmt->execute()){$result = $stmt->get_result();
											$count = $result->num_rows;
											 $array = $result->fetch_assoc();
											 $uid = $array['uid'];
										
											}
						$stmt->close();
						
						$select = "SELECT id FROM call_log WHERE uid = ?";
						$stmt = $conn->prepare($select);
						$stmt->bind_param('s', $uid);
						if($stmt->execute()){$result = $stmt->get_result();
											 
											$rowcount = $result->num_rows;
 											
											 $response->say("You have".$rowcount."open reports");
											 while($array = $result->fetch_assoc()){
												 
												
											 }
											 }
						
						
							 $gather = $response->gather(array(
'action' => $domain.'/x/alt_result-2.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 6));

//Greeting and providing directions to locator of pet
					
$gather->say("Enter the 6-digit call code that you've been sent");
$gather->say('To go back to the previous menu, press star.');
												 echo $response->asXML();
						$stmt->close();
						
						
						
						
					   }else{$response->say("youve pressed an invalid number"); $response->redirect($domain.'x/call.php'); echo $response->asXML();
													  }

?>