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

$usrsel = "SELECT * FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($usrsel);
$stmt->bind_param('s', $from);
if($stmt->execute()){$result = $stmt->get_result();
		
					 $firstcount = $result->num_rows;
					$array = $result->fetch_assoc();
					 
					 
					 //
					 $selecttwo = "SELECT * FROM call_log WHERE uid = ?";
					 $stmtt = $conn->prepare($selecttwo);
					 $stmtt->bind_param('s', $array['uid']);
					 if($stmtt->execute()){$resulttwo = $stmtt->get_result();
										  $arraytwo = $resulttwo->fetch_assoc();
										   if(is_null($arraytwo)){echo "blank";}else{echo "log id is $arraytwo[id]";}
										  }
					 $stmt->close();
					 $stmtt->close();
					 //
					 
				 if($firstcount == "0"){
						 
						 $response->say("Hello, Guest");
						 //echo $response->asXML();
					 }else{
						 $response->say("Welcome back, $array[firstname]");
						 //echo $response->asXML();
				 }
					
					

					
					 if($firstcount != "0"){

						 
						 $gather = $response->gather(array(
'action' => $domain.'/x/alt_result.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 1));

//Greeting and providing directions to locator of pet
$gather->say('Welcome to Lost Pet Connect.');
$gather->say('To connect with one of your cases, press 1.');
$gather->say('If you have found someone elses pet, press 2.');
$response->say('Thank you for using Lost Pet Connect');
						 header("content-type: text/xml");
echo $response->asXML();
	//					 header("content-type: text/xml");
//echo $response->asXML();
					 

				}else{
	
	
	$gather = $response->gather(array(
'action' => $domain.'/x/call_result.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 6));

//Greeting and providing directions to locator of pet
					
$gather->say('Welcome to Lost Pet Connect. So that we can connect you with the owner of the pet, please enter your 6-digit call code.');
$response->say('Thank you for using Lost Pet Connect');
header("content-type: text/xml");
echo $response->asXML();
					 
					 
					 }
					 
					}

					?>