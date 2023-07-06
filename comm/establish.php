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
					 
					 //Var holding composers autoload path
					 $composer_path = $array['composer_path']; 
					}
$stmt->close();

//Composers autoload
require($composer_path);

use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;


header("content-type: text/xml");
					 
//TTS response locator hears once pet owners answers call 
$response->say("The owner of the pet has answered and you are now being connected.");
echo $response->asXML();
					
?>