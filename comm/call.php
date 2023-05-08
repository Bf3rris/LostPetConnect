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

$usrsel = "SELECT uid FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($usrsel);
$stmt->bind_param('s', $from);
if($stmt->execute()){$result = $stmt->get_result();
					 
					$rowcount = $result->num_rows;
					 
					 if($rowcount != 0){
					$array = $result->fetch_assoc();
					 $count = $result->num_rows;
					 
					 
					 $uid = $array['uid'];
						 
						 
						 
						 
					 }
						 $stmt->close();

					 
					}
					 
					 if($rowcount != 0){

					 
$check = "SELECT * FROM call_log WHERE uid = ?";
$stmt = $conn->prepare($check);
$stmt->bind_param('s', $uid);
if($stmt->execute()){$result = $stmt->get_result();
					 $array = $result->fetch_assoc();
					 $id = $array['id'];
					 
					
					}
$stmt->close();

						 ////////////////////////
						 $gather = $response->gather(array(
'action' => $domain.'/x/alt_result.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 1));

//Greeting and providing directions to locator of pet
					
$gather->say('To connect with one of your open cases  press 1');
$gather->say('If youve found someone elses pet, press 2');
echo $response->asXML();
						 
						 ////////////////////////
						 
						 
						 
//$response->say("You are being connected.");
	//$dial = $response->dial();
	//$dial->queue($id, ['url' => 'http://13.59.192.46/x/establish.php']);
	//header("Content-type: text/xml");
	//echo $response->asXML();
	
	
	
	
}else{

//Gathering PIN that is generated on pet details page / aides in routing call to correct pet owner
						
						
$gather = $response->gather(array(
'action' => $domain.'/x/call_result.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 6));

//Greeting and providing directions to locator of pet
					
$gather->say('Welcome to Juniors Pokemon Shop, the largest Pokemon Shop in the Midwest, located in Xenia Ohio.');
$response->say('Thank you for using Lost Pet Connect');
header("content-type: text/xml");
echo $response->asXML();
}

					 
?>