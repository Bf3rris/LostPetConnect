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

$response->dial('+19372347782');
echo $response;

//Gathering PIN that is generated on pet details page / aides in routing call to correct pet owner
$gather = $response->gather(array(
'action' => $domain.'/x/call_result.php',
	'input' => 'dtmf',
	'timeout' => 15,
	'method' => 'POST',
	'numDigits' => 6));

//Greeting and providing directions to locator of pet
$gather->say('Welcome to Lost Pet Connect. So that we can connect you with the owner of the pet, please enter your 6-digit call code. ');
$response->say('Thank you for using Lost Pet Connect');

header("Content-type: text/xml");
echo $response->asXML();



?>