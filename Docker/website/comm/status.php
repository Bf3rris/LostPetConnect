<?php
require('../connection.php');



//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT domain, composer_path FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding url of site
					 $domain = $array['domain'];
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path']; 
					 
					
					}
$stmt->close();




//Composers autoload
require($composer_path);

use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;


$callsid = $_POST['CallSid'];
$callstatus = $_POrST['CallStatus'];

if($_POST['CallStatus'] == "completed"){$answer = "0";}else{$answer = "1";}




$sql = "UPDATE call_log SET active_call = ? WHERE call_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $answer, $callsid);
$stmt->execute();
$stmt->close();
?>