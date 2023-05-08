<?php

require('../connection.php');
require_once('vendor/autoload.php');
$mxid = rand(1111,9999);
$non = "1";

use \SignalWire\LaML\MessageResponse;

$response = new MessageResponse;



//retrieving message details
$body = $_REQUEST['Body'];
$mid = $_REQUEST['MessageSid'];
$from = $_REQUEST['From'];
$to = $_REQUEST['To'];



//
$cid = substr($from, 2);
$time = date('m-d-y / h:i');
$rs = "2";



//check to see if number exists as a registered user
$search = "SELECT * FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $cid);
if($stmt->execute()){
	
	$result = $stmt->get_result();


//counting rows for result
$countone = $result->num_rows;
					}
$stmt->close();



//if phone number is registered
if($countone == 1){
	
	
	$array = $result->fetch_assoc();
	$uid = $array['uid'];
	
	//checking for keywords
	
	
	//if body contains accept
	
	
	
	if(strstr($body, "Accept")){$exploed = explode(" ", $body);
							   
							  //retrieving locatirid from id
								$lid = "SELECT * FROM message_log WHERE id = ?";
								$stmt = $conn->prepare($lid);
								$stmt->bind_param('i', $exploed[1]);
								if($stmt->execute()){$result = $stmt->get_result();
													$array = $result->fetch_assoc();
													 
													$locatorid = $array['locatorid'];
													
													}
							   $stmt->close();
							   
								$response->message('Locators phone number is:' . $locatorid);
								header('content-type: text/xml');
								echo $response->asXML();
								
								
							   }
	
	
	//if body contains stop
	if(strstr($body, "Stop")){
		
		
		//Separate id
	$stoptext = explode(" ", $body);
		
		//
		$restrict = "2";
		
		
		//select * from restricted
		$grab = "SELECT * FROM users WHERE uid = ?";
		$stmt = $conn->prepare($grab);
		$stmt->bind_param('s', $uid);
		if($stmt->execute()){$result = $stmt->get_result();
							$array = $result->fetch_assoc();
							 $restricted_list = $array['restricted'];
							}
		$stmt->close();
		
		
		//Select locator id to block
		$up = "SELECT * FROM message_log WHERE id = ?";
		$stmt = $conn->prepare($up);
		$stmt->bind_param('s', $stoptext[1]);
		if($stmt->execute()){$result = $stmt->get_result();
							$array = $result->fetch_assoc();
							 $bid = $array['locatorid'];
							}
		$stmt->close();
		
		//add locator to restrcted list
		$ul =  ",$bid,".$restricted_list;
		
		//updated restricted column
	$stopsql = "UPDATE users SET restricted = ? WHERE uid = ?";
		$stmt = $conn->prepare($stopsql);
		$stmt->bind_param('ss', $ul, $uid);
		$stmt->execute();
		$stmt->close();
		
	}
	
}else{
	
	//if phone number is not registered
	
	
	
	
	
	
	if(strstr($body, "Your")){
		
		
		
		//send message to owner
		//translate pet it to owners phone number
		
		
		//explode to get pet id
		$explode = explode(":", $body);
		
		$missing = "2";
		//retrieve owners uid from pet id
		$retrieve = "SELECT * FROM pets WHERE pid = ? AND status = ?";
		$stmt = $conn->prepare($retrieve);
		$stmt->bind_param('si', $explode[1], $missing);
		if($stmt->execute()){$result = $stmt->get_result();
							$array = $result->fetch_assoc();
							 $uid = $array['uid'];
							 $pet_name = $array['name'];
							}
		$stmt->close();
		
		//retrieve owners phone number
		//checking for restrictions 
		$pn = "SELECT * FROM users WHERE uid = ?";
		$stmt = $conn->prepare($pn);
		$stmt->bind_param('s', $uid);
		if($stmt->execute()){$result = $stmt->get_result();
						   $array = $result->fetch_assoc();
							$phone_number = $array['phone_number'];
							 $blockarray = $array['restricted'];
							}
		$stmt->close();

		
$one = explode(",", $blockarray);
		
		if(in_array($from, $one)){
							   
							   
							   //text response informing of being blocked
								$response->message("how'd you do that?");
								header("content-type: text/xml");
								echo $response->asXML();
							   
							   }else{
			
			
			//number isnt blocked
			
			
		}
		
		
		//insert message details into db
		$blank = "";
		 //
							 $insert = "INSERT INTO message_log (id, mid, locatorid, uid, petid, body, time, status, locator_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
							 $stmt = $conn->prepare($insert);
							 $stmt->bind_param('issssssis', $mxid, $mid, $from, $uid, $explode[1], $body, $time, $non, $blank);
							 $stmt->execute();
							 $stmt->close();
		
		
		
		//Send actual message to owner
		
		echo "<Response><Message to='+1$phone_number'>[Lost Pet Connect] $pet_name has been found!</Message>
	
		<Message to='+1$phone_number'>Reply 'Accept $mxid' to exchange contact info.</Message>
		
		</Response>";
				

		
		//<Response><Message to='$from'>What is your name?</Message></Response>";
	
		
		
		header("content-type: text/xml");
		
		//echo $response->asXML();
		
		

		
		

		
	}
	
	
	
	
	
	
}



?>