<?php

//Starting mysqli connection
require('../connection.php');

//Composers autoloader
 require 'vendor/autoload.php';


  use SignalWire\LaML;
  $response = new LaML;


//Posting sms details and setting vars
$body = $_REQUEST['Body'];
$mid = $_REQUEST['MessageSid'];
$from = $_REQUEST['From'];
$to = $_REQUEST['To'];
$sent = $_REQUEST['Sent'];


//Checking for action
if(strstr($body, "Connect")){
	
	//Selecting 
	$connect = "SELECT * FROM messages WHERE uid = ?";
	$stmt = $conn->prepare($connect);
	$stmt->bind_param('s', $from);
	if($stmt->execute()){$result = $stmt->get_result();
						$second = $result->fetch_assoc();
						 $owner = $second['uid'];
							 $locator = $second['locatorid'];
					
						}
	$stmt->close();
						$cxm = "Locators contact number: $locator";

	echo"
<Response>
    <Message to='$from'>$cxm</Message>
</Response>";
	
	
}else{

//////Initial message
if(strstr($body, "Your pet has been found:")){

	
	//explode for
	$code = explode(":", $body);
	
	
	
	//automated response
$msg = "Your pet has been located. Reply 'Connect' to exchange contact info.";
	
	
	//Querying pet table to cross petid to get uid to get phone number
	$return = "SELECT uid FROM pets WHERE pid = ?";
	$stmt = $conn->prepare($return);
	$stmt->bind_param('s', $code[1]);
	if($stmt->execute()){$result = $stmt->get_result();
						$data = $result->fetch_assoc();
						 $uid = $data['uid'];
						}
		$stmt->close();
		
	//Return owners phone number to initiate message
	$phonenumber = "SELECT phone_number FROM users WHERE uid = ?";
	$stmt = $conn->prepare($phonenumber);
	$stmt->bind_param('s', $uid);
	if($stmt->execute()){$result = $stmt->get_result();
						$detail = $result->fetch_assoc();
$number = $detail['phone_number'];
}
$stmt->close();
	
	
	//Insert into message log
	$insert = "INSERT INTO messages (mid, locatorid, uid, petid, body) VALUES (?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($insert);
	$stmt->bind_param('sssss', $mid, $from, $uid, $code[1], $body);
	$stmt->execute();
	$stmt->close();
}


?>
<?php



	echo"
<Response>
    <Message to='+1$number'>$msg</Message>
</Response>";
}
?>