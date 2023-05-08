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


$search = "SELECT * FROM call_log WHERE code = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $digits);
if($stmt->execute()){$result = $stmt->get_result();
					
					$results = $result->num_rows;
				$array = $result->fetch_assoc();
					$id = $array['id'];
				   }
$stmt->close();


$response->enqueue($id, 'http://13.59.192.46/x/establish.php');
echo $response->asXML();


?>