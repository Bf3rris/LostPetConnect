<?php

//Starting mysqli connection
require('../connection.php');

//Compsoers autoload
require 'vendor/autoload.php';

//Setting date var / used for database call_log data
$date = date('m-d-y');

use \SignalWire\LaML\VoiceResponse;
$response = new VoiceResponse;

//Posting caller id number of locator of pet
$digits = $_REQUEST['Digits'];

//Verifying call code is in database and call is received during the window
//Call PIN aides in routing call to owner and prevents unwanted calls from being received by pet owner
$cc = "SELECT code FROM call_log WHERE date = ? AND code = ?";
$stmt = $conn->prepare($cc);
$stmt->bind_param('ss', $date, $digits);
if($stmt->execute()){$result = $stmt->get_result();
		
	     //Variable containing nuber result of match
		$count = $result->num_rows;
					}
$stmt->close();

//If there is a match with call pin and date in table
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

	
	
//If table doesn't contain match of pet id and call log
if($count == "0"){$response->say('There has been an error');}else{

	//Retrieving uid of pet owner to use to retrieve phone number
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
					 
					 //Fething array for data
					$data = $result->fetch_assoc();
					 //Setting var holding pet owners phone number
					 $number = $data['phone_number'];
					
					}
$stmt->close();
	//Placing call to owner of pet
	$response->dial("+1".$number);
$response->say('Thank you for using Lost Pet Connect');
	
	
header('content-type: text/xml');
echo $response->asXML();
}
}else{
	
	//Notifying caller if incorrect call PIN being provided
	$response->say("The PIN that you've provided is invalid.");
	
	//Redirecting caller to main menu to input code again
	$response->redirect("http://13.59.192.46/x/call.php");
	echo $response->asXML();
}
?>
