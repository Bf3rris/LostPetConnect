<?php

require('../connection.php');
 require_once 'vendor/autoload.php';


  use \SignalWire\LaML\MessageResponse;
use \SignalWire\LaML\VoiceResponse;

$responseb = new VoiceResponse;
  $response = new MessageResponse;


//retrieving message details
$body = $_REQUEST['Body'];
$mid = explode("-", $_REQUEST['MessageSid']);
$from = $_REQUEST['From'];
$to = $_REQUEST['To'];


//
$cid = substr($from, 2);
$search = "SELECT uid FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $cid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 $count = $result->num_rows;
					 if($count == 0){}else{
					$array = $result->fetch_assoc();
					 
					 $cuid = $array['uid'];
					}}
$stmt->close();



//
$cidd = "SELECT * FROM message_log WHERE uid = ?";
$stmt = $conn->prepare($cidd);
$stmt->bind_param('s', $cuid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 $array = $result->fetch_assoc();
					$rows = $result->num_rows;
					 if($rows == 0){$locatorid = null;}else{
					 $locatorid = $array['locatorid'];
					}}
$stmt->close();


//
if($rows != 0){

	
	//
$oe = explode(" ", $body);
	
	//
	if(strstr($body, "Call")){
		
			
			
		
		//header('Content-type: text/xml');
		
$dial = $responseb->dial('', array('callerid' => '+19374539293'));
		$dial->number('+19374431724');
	echo $responseb;


	
		
			
	}elseif(strstr($body, "Close")){
		
		
		$newstatus = "2";
		$closesql = "UPDATE message_log SET status = ? WHERE uid = ? AND mid = ?";
		$stmt = $conn->prepare($closesql);
		$stmt->bind_param('sss', $newstatus, $cuid, $oe[1]);
			$stmt->execute();
		$stmt->close();
		
		header('Content-type: text/xml');
	echo"
<Response>
    <Message to='$from'>Status changed to closed.</Message>
</Response>";
		
		
	}else{
		
		header('Content-type: text/xml');
	echo"
<Response>
    <Message to='$from'>Last option</Message>
</Response>";
		
	}
		

	
	
	$owner_number = substr($from, 2);
	$connect = "SELECT uid FROM users WHERE phone_number = ?";
	$stmt = $conn->prepare($connect);
	$stmt->bind_param('s', $owner_number);
	if($stmt->execute()){$result = $stmt->get_result();
						$second = $result->fetch_assoc();
						 $owner = $second['uid'];
							
						
						 	}
	$stmt->close();
	
	
	$ml = "SELECT * FROM message_log WHERE uid = ?";
	$stmt = $conn->prepare($ml);
	$stmt->bind_param('s', $owner);
	if($stmt->execute()){
		$result = $stmt->get_result();
		$array = $result->fetch_assoc();
		$locator = $array['locatorid'];
		
	}
	$stmt->close();
	
}else{

//Initial message
if(strstr($body, "Your pet has been found:")){

	
	//explode for
	$code = explode(":", $body);
	
	
	
	//automated response
	
	
	//Querying pet table to cross petid to get uid to get phone number
	$return = "SELECT uid, name FROM pets WHERE pid = ?";
	$stmt = $conn->prepare($return);
	$stmt->bind_param('s', $code[1]);
	if($stmt->execute()){$result = $stmt->get_result();
						$data = $result->fetch_assoc();
						 $name = $data['name'];
						 $uid = $data['uid'];
						 
						}
		$stmt->close();
	
	//message for...[]
		$msg = "$name has been located. Reply 'Call 123' to get connected.";

	//Return owners phone number to initiate message
	$phonenumber = "SELECT phone_number FROM users WHERE uid = ?";
	$stmt = $conn->prepare($phonenumber);
	$stmt->bind_param('s', $uid);
	if($stmt->execute()){$result = $stmt->get_result();
						$detail = $result->fetch_assoc();
$number = $detail['phone_number'];
}
$stmt->close();
	
	//simple timestamp
	$time  = date('m.d.y')."-".date('h:i');
		
	
	//default status - 1 = open / 2 = closed
	///
	$status = "1";
	
	//Insert into message log
	$insert = "INSERT INTO message_log (mid, locatorid, uid, petid, body, time, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($insert);
	$stmt->bind_param('sssssss', $mid[1], $from, $uid, $code[1], $body, $time, $status);
	$stmt->execute();
	$stmt->close();
}



	echo"
<Response>
    <Message to='+1$number'>$msg</Message>
</Response>";
}
?>