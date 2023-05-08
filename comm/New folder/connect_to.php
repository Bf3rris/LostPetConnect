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

	  use SignalWire\Rest\Client;


$from = $_REQUEST['From'];

$date = date('m-d-y');
$digits = $_REQUEST['Digits'];

$cc = "SELECT code FROM call_log WHERE date = ? AND code = ?";
$stmt = $conn->prepare($cc);
$stmt->bind_param('ss', $date, $digits);
if($stmt->execute()){$result = $stmt->get_result();
		
	     //Variable containing nuber result of match
		$count = $result->num_rows;
					}
$stmt->close();

//If call pin data exists in table the call will be allowed
if($count != "0"){

	
	//Updating call log with phone number of caller
	$updatesql = "UPDATE call_log SET from_number = ? WHERE code = ?";
	$stmt = $conn->prepare($updatesql);
	$stmt->bind_param('ss', $_REQUEST['From'], $digits);
	$stmt->execute();
	$stmt->close();

//Giving caller status of successful connection with owner of pet
$response->say('You are now being connected with the owner of the Pet.');

//Verifying call pin and pet id match in database
$search = "SELECT pid FROM call_log WHERE code = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $digits);
if($stmt->execute()){$result = $stmt->get_result();

//Var containg number result of match
$count = $result->num_rows;

//Fetching array for data
$data = $result->fetch_assoc();
					 
//Seting pet id as var
$pid = $data['pid'];

}
$stmt->close();

	
	
//Response if query can't be matched
if($count == "0"){$response->say('There seems to be a problem with your call code, please try again.');}else{

	//Retrieving uid of pet owner to use to retrieve phone number to connect call to
$petid = "SELECT uid FROM pets WHERE pid = ?";
$stmt = $conn->prepare($petid);
$stmt->bind_param('s', $pid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					//Fetching array for data
					$data = $result->fetch_assoc();
					 
					 //Var holding uid of pet owner
					$uid = $data['uid'];
					}
$stmt->close();
	
//Selecting pet owners phone number to place call to
$owner = "SELECT phone_number FROM users WHERE uid = ?";
$stmt = $conn->prepare($owner);
$stmt->bind_param('s', $uid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Fething array for phone number
					$data = $result->fetch_assoc();
					 //Setting var holding pet owners phone number
					 $number = $data['phone_number'];
					
					}
$stmt->close();
	//header('content-type: text/xml');
	//Placing call to owner of pet
	
	$one = "SELECT id FROM call_log WHERE from_number = ?";
	$stmt = $conn->prepare($one);
	$stmt->bind_param('s', $from);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 
						 //Setting var to use as name of queue
						 $idd = $array['id'];
						}
	$stmt->close();
	
	//Sending text to owner 
	$PROJECT_ID = "21a6a9d2-411f-43ca-aff7-8608e66f40e4";
	$API_TOKEN = "PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7";
	$SPACE_URL = "bf3rris.signalwire.com";
$TO_NUMBER = "+1".$number;
$FROM_NUMBER = "+19374539293";
$message = "Dial +19374539293 to get connected with the locator";
	$client = new Client($PROJECT_ID, $API_TOKEN, array("signalwireSpaceUrl" => $SPACE_URL));
  $message = $client->messages->create($TO_NUMBER, // to
                             array("from" => $FROM_NUMBER, "body" => $message)
                    );
		
	
	//Adding caller to queue 
	$response->enqueue($idd);
	
	
	//Played after other party disconnects
//	$response->redirect($domain."/x/call.php");
$response->say('Thank you for using Lost Pet Connect');
	
header("content-type: text/xml");
	//echo 
		//"<Response>
	//	<Enqueue waitUrl='http://13.59.192.46/x/12.php'>$idd</Enqueue></Response>";
echo $response->asXML();
}}
?>