<?php

//Start mysqli connection
require('../connection.php');

//Site settings config ID
$configid = "1";

//Selecting composer autoload path from site settings
$settings_sql = "SELECT composer_path FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path']; 
				
					}
$stmt->close();

//Composers autoload
require($composer_path);

use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;

//Var holding incoming call sid
$sid = strip_tags($_POST['CallSid']);

//Var holding incoming callers phone number
$from = strip_tags($_POST['From']);


//TTS if owner can't be reached
$response->say("The owner of the pet could not be reached. Please try again later.");
$response->say("Thank you for using Lost Pet Connect.");
$response->hangup();
header("content-type: text/xml");
echo $response->asXML();


?>