<?php
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


header("Content-type: text/xml");
//$from = $_REQUEST['From'];

echo "<Response><Sms>Message</Sms><Say>This is something you'll hear while you're waiting for one of our support specialists to become available.</Say><Pause length='8'/></Response>";

//$response->dial('+19374431724');
//$response->asXML();
?>