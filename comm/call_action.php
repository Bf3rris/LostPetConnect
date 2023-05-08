<?php

require('../connection.php');
require('vendor/autoload.php');
  use \SignalWire\LaML\VoiceResponse;


$response = new VoiceResponse;

	$to = substr($_REQUEST['To'], 2);
$digits = $_REQUEST['Digits'];

if($digits == "1"){
	
	
	$response->say("You have pressed 1");

	
//	$dial = $response->dial();
//	$dial->queue($id, ['url' => 'http://13.59.192.46/x/establish.php']);

	
	//
	
	//
	$select_number = "SELECT * FROM users WHERE phone_number = ?";
	$stmt = $conn->prepare($select_number);
	$stmt->bind_param('s', $to);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 $uid = $array['uid'];
						}
	$stmt->close();
	
	
	$select_log = "SELECT * FROM call_log WHERE uid = ?";
	$stmt = $conn->prepare($select_log);
	$stmt->bind_param('s', $uid);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 $id = $array['note'];
						 //$realid = $array['call_id'];
						}
	$stmt->close();
	
	
	
	//
	$dial = $response->dial();
	$dial->queue($id, ['url' => 'http://13.59.192.46/x/establish.php']);
	
	
}else{
	
	$response->say("You have pressed 2");
	
	
	//
	$select_number = "SELECT * FROM users WHERE phone_number = ?";
	$stmt = $conn->prepare($select_number);
	$stmt->bind_param('s', $to);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 $uid = $array['uid'];
						}
	$stmt->close();
	
	
	$select_log = "SELECT * FROM call_log WHERE uid = ?";
	$stmt = $conn->prepare($select_log);
	$stmt->bind_param('s', $uid);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 $id = $array['note'];
						 $realid = $array['call_id'];
						}
	$stmt->close();
	
	
	
	//$response->redirect("http://13.59.192.46/x/call_direct.php");
	$call = $client->calls($realid)
                 ->update(array("url" => "http://13.59.192.46/x/no_answer.php"));
	
}



header("content-type: text/xml");
echo $response->asXML();


?>