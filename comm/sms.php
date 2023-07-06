<?php


//Starting MySQL connection
require('../connection.php');

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

					}
$stmt->close();
//Composers autoload
require_once($composer_path);


use \SignalWire\LaML\MessageResponse;
$response = new MessageResponse;


//Requesting incoming message details
$body = strtolower(strip_tags($_POST['Body']));
$mid = strip_tags($_POST['MessageSid']);
$from = strip_tags($_POST['From']);
$to = strip_tags($_POST['To']);


//Inventionally empty var
$blank = "";


//Stripping first 2 characters of senders phone number to match to registered user
$from_id = substr($from, 2);

//Matching number to registered user
$search = "SELECT uid FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $from_id);
if($stmt->execute()){
	
$result = $stmt->get_result();
	
	
	//Column count of registered user
$reg_count = $result->num_rows;
	
if($reg_count == 0){
	
	/////unregistered phone number sstuff
	

		//Checking for keywords from initial prefilled web link
		if(strstr($body, "your")){
		
			//Extracting pet id from initial located message
		$explode = explode(":", $body);
		
		
			//Translate pet id to the owners uid
		$retrieve = "SELECT uid, name FROM pets WHERE pid = ?";
		$stmt = $conn->prepare($retrieve);
		$stmt->bind_param('s', $explode[1]);
		if($stmt->execute()){$result = $stmt->get_result();
							$array = $result->fetch_assoc();
							 
							 //Var holding pet owners uid
							 $uid = $array['uid'];
							 
							 //Var holding pets name
							 $pet_name = $array['name'];
							}
		$stmt->close();
			
			
			
			
			
		//Checking owners restricted phone number list to allow / block message
		$pn = "SELECT phone_number, restricted FROM users WHERE uid = ?";
		$stmt = $conn->prepare($pn);
		$stmt->bind_param('s', $uid);
		if($stmt->execute()){$result = $stmt->get_result();
						   $array = $result->fetch_assoc();
							 
							 //Pet owners phone number
							$phone_number = $array['phone_number'];
							 
							 //Array containing restricted phone numbers for this specific user
							$blockarray = $array['restricted'];
							}
		$stmt->close();
		}
	
	if(strstr($blockarray, $from)){
		
		//If incoming # is on users restricted list
		//reply is a notification of being blocked
		
		header("content-type: text/xml");
		$response->message("You are not permitted to contact this user.");
		echo $response->asXML();
	
	}else{
		
		//Var holding date incoming sms was received
		$date = date('m.d.y');
		
		//Var holding time incoming sms was received
		$time = date('h:i');
		
		
		 //insert message details into db
							 $insert = "INSERT INTO message_log (id, mid, from_number, uid, petid, body, time, date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
							 $stmt = $conn->prepare($insert);
							 $stmt->bind_param('issssssss', $mxid, $mid, $from, $uid, $explode[1], $body, $time, $date, $blank);
							 $stmt->execute();
							 $stmt->close();
		
		
		
		//Sending message to owner of pet of location status and locators phone number
		//2nd message assures locator that message has been received
		echo "<Response>
		
		
		
		<Message to='+1$phone_number'>[Lost Pet Connect] $pet_name has been located.</Message>
		<Message to='+1$phone_number'>Locators #: $from</Message>

		
		<Message to='$from'>Thank you for your report! The owner has been notified. Please standby.</Message></Response>";
		header("content-type: text/xml");
		$response->asXML();
		
	}}else{
	
	//Simple reply with link to access account login if registered and uses 'help' keyword
	
	if(strstr($body, "help")){
	header("content-type: text/xml");
		$response->message("View your calls and text messages in detail online @ $domain/members");
		echo $response->asXML();
	}
}}
		?>