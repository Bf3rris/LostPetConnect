<?php


//Starting MySQL connection
require('../connection.php');

//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT domain, composer_path, xfn FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path']; 
					 
					 //Var holding SignalWire phone number
					 $xfn = $array['xfn'];
					 
					
					}
$stmt->close();

//Composer autolload
require_once($composer_path);



use \SignalWire\LaML\VoiceResponse;

$response = new VoiceResponse;

//Number that is to be called
$to = strip_tags($_POST['To']);

//Digits received to handle call direction
$action = strip_tags($_POST['Digits']);

//Var containing modified phone number used for db matching
$sub = substr($to, 2);


//Selecting call_out # (number of locator) from DB
$co = "SELECT call_out FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($co);
$stmt->bind_param('s', $sub);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Number that is to be dialed (Locator of pet)
					 $colpn = $array['call_out'];
					}
$stmt->close();

//Pet owner will be connected to locator of pet
if($action == "1"){
	$response->say("Please hold while your call is being connected.");
		
	$dial = $response->dial('',array( 'callerId' => $xfn ));
  $dial->number($colpn);

}else{
	
	//Ending call if 2 is pressed
	$response->say("You have canceled calling the locator of your pet.");
	$response->say("Thank you for using Lost Pet Connect, goodbye!");
	$response->hangup();
	
}
	header("content-type: text/xml");
echo $response->asXML();
	

?>