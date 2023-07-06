<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

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

//Composers autoload
require_once($composer_path);


use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;

//Locators phone number call is being placed to
$to = strip_tags($_POST['To']);

$gather = $response->gather(array(
'action' => $domain.'/comm/call_complete.php',
	'input' => 'dtmf',
	'timeout' => 10,
	'method' => 'POST',
	'numDigits' => 1));

//Greeting pet owner 
$gather->say("Hello from Lost Pet Connect.");

//Announcing selections to handle the call
$gather->say("Press 1 to get connected with the locator of your pet, press 2 or simply hangup to cancel.");

header("content-type: text/xml");
echo $response->asXML();

?>