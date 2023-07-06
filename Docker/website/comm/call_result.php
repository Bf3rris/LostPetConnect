<?php

//Starting MySQL connection
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
					 
					 
					 //Var holding signalwire phone number
					 $xfn = getenv('SIGNALWIRE_PHONE_NUMBER');
					 
					 //Var holding SignalWire space url
					 $space_url = getenv('SIGNALWIRE_SPACE_URL');
					 
					 //Var holding SignalWire project id
					$project_id = getenv('SIGNALWIRE_PROJECT_ID');
					 
					 //Var holding SignalWire token
					 $token = getenv('SIGNALWIRE_AUTH_TOKEN');

					}
$stmt->close();


//Composers autoload path
require_once($composer_path);


use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;


$response = new VoiceResponse;

//Setting var for call sid
$callsid = strip_tags($_POST['CallSid']);

//Var containing locators phone number
$from = strip_tags($_POST['From']);

//Posting caller id number of locator of pet
$digits = strip_tags($_POST['Digits']);

//Verifying call pin is exists in db 
//Call PIN aides in routing call to owner and prevents unwanted calls from being received by pet owner
$cc = "SELECT * FROM call_log WHERE code = ?";
$stmt = $conn->prepare($cc);
$stmt->bind_param('s', $digits);
if($stmt->execute()){$result = $stmt->get_result();
		
	     //Variable containing nuber result of match
		$count = $result->num_rows;
					}
$stmt->close();

//If call pin data exists in table the call will be allowed
if($count != "0"){

	
	//Updating call log with phone number of caller
	$updatesql = "UPDATE call_log SET from_number = ?, call_id = ? WHERE code = ?";
	$stmt = $conn->prepare($updatesql);
	$stmt->bind_param('sss', $from, $callsid, $digits);
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
if($count == "0"){$response->say('There seems to be a problem with your call code, please try again.');
				 $response->redirect("$domain/comm/call.php");
				 }else{

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
	
//Selecting pet owners phone number to place call to / checking restricted list column
$owner = "SELECT phone_number, restricted FROM users WHERE uid = ?";
$stmt = $conn->prepare($owner);
$stmt->bind_param('s', $uid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Fething array for phone number
					$data = $result->fetch_assoc();
					 //Setting var holding pet owners phone number
					 $number = $data['phone_number'];
					
					 //Restricted phone number column
					 $restricted = $data['restricted'];
					}
$stmt->close();
	
	
	////if incoming # is contained within restricted column call will be answered and caller notified

	if ($restricted !== null && $from !== null && strstr($restricted, $from)) {
    header("content-type: text/xml");
    $response->say("You are not permitted to contact this customer");
    $response->say("Thank you for using Lost Pet Connect, goodbye.");
    $response->hangup();
    echo $response->asXML();
		
		
		
	}else{
	
	
	

	
	///Start call to pet owner
	//Starting client
  $client = new Client($project_id, $token, array("signalwireSpaceUrl" => "$space_url"));

  $call = $client->calls
                 ->create("+1".$number, // to
                          "$xfn", // from
                          array("url" => "$domain/comm/call_direct.php",
							   "MachineDetection" => "Enable",
								"AsyncAmd" => "true",
								"Timeout" => 27,
								"MachineWordsThreshold" => 3,
							   "MachineDetectionTimeout" => 3

							   )
                 );
	
	//Variable containing call sid
	$cidd = $call->sid;
	
	//Update call log with call out number
	$out_id_update = "UPDATE call_log SET out_id = ? WHERE from_number = ? AND code = ?";
	$stmt = $conn->prepare($out_id_update);
	$stmt->bind_param('sss', $cidd, $from, $digits);
	$stmt->execute();
	$stmt->close();

	
	
	//Var containing call sid
	$cid = $call->sid;
		
		
		

		$hash = explode('-', strip_tags($_POST['CallSid']));
		
		//Var setting name of wait queue
		$queue_name = "$uid-$hash[1]";
	
	//Updating call log with call sid  and queue name () / used to check status of call
	$one = "UPDATE call_log SET tag = ? WHERE uid = ? AND code = ?";
	$stmt = $conn->prepare($one);
	$stmt->bind_param('sss', $queue_name, $uid, $digits);
	$stmt->execute();
						 
	$stmt->close();
	
	
	
	
		

	
	//Adding caller to queue to wait for pet owner to answer
	$response->enqueue($queue_name,array('waitUrl' => $domain.'/comm/wait.php'));
	
	
	
		
		
			
		
		
	//Locator hears TTS if pet owner owner disconnects from call first
$response->say('Thank you for using Lost Pet Connect');
$response->hangup();


echo $response->asXML();
	
	
	}}
}else{
	
	//Notifying caller of incorrect call code being provided
	$response->say("The PIN that you've provided is invalid. Please try again.");
	
	//Redirecting caller to main menu to retry call code input
	$response->redirect($domain."/comm/call.php");
	echo $response->asXML();
}
?>
