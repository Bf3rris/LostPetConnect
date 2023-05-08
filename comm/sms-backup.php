<?php

require('../connection.php');
require_once 'vendor/autoload.php';
$mxid = rand(1111,9999);


use \SignalWire\LaML\MessageResponse;

$response = new MessageResponse;


//retrieving message details
$body = $_REQUEST['Body'];
$mid = $_REQUEST['MessageSid'];
$from = $_REQUEST['From'];
$to = $_REQUEST['To'];


//
$cid = substr($from, 2);



//check to see if number exists as a registered user
$search = "SELECT uid FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('s', $cid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 
					 
					 
					 $time = date('m-d-y / h:i');
					 
					 $rs = "2";
					 
					 //counting rows for result
					 $countone = $result->num_rows;
					 //if count = 0 do nothing
					 if($countone == 0){
						 
						 
						 
						 //allowed status
						 $allowed = "2";
						 
						 $non = "1";
						 
						 //checking for free message status
						 $check = "SELECT * FROM message_log WHERE locatorid = ? AND status = ?";
						 $stmt = $conn->prepare($check);
						 $stmt->bind_param('si', $from, $rs);
						 if($stmt->execute()){$result = $stmt->get_result();
											 $array = $result->fetch_assoc();
											  $actual = $array['status'];
											  $messageid = $array['mid'];
											  $count = $result->num_rows;
											  
											  echo "count = $count";
											 }
						 $stmt->close();
						 if($count == 1){
							 
							 //get owners uid
							 $sel = "SELECT * FROM message_log WHERE mid = ?";
							 $stmt = $conn->prepare($sel);
							 $stmt->bind_param('s', $messageid);
							 if($stmt->execute()){$result = $stmt->get_result();
												 $array = $result->fetch_assoc();
												  $owner_uid = $array['uid'];
												  
												 }
							 $stmt->close();
							 
							 
							 //get owners phone number from uid
							 $tpn = "SELECT * FROM users WHERE uid = ?";
							 $stmt = $conn->prepare($tpn);
							 $stmt->bind_param('s', $owner_uid);
							 if($stmt->execute()){$result = $stmt->get_result();
												 $array = $result->fetch_assoc();
												  $owner_number = $array['phone_number'];
												 
												 }
							 $stmt->close();
							 
							 
							 
							 
							 echo "<Response><Message to='+19372347782'>This is just a test.</Message></Response>";
							 
							 
						 //for general data
						
						 }else{
						//checking message for certain words
						 if(strstr($body, "Your")){
							 
							 
							 
							
							 //ecplode to get pet id
							 $piece = explode(":", $body);
							 
							 
							 
							  //translate pet id to uid and phone #
							 $tro = "SELECT * FROM pets WHERE pid = ?";
							 $stmt = $conn->prepare($tro);
							 $stmt->bind_param('s', $piece[1]);
							 if($stmt->execute()){$result = $stmt->get_result();
											  $array = $result->fetch_assoc();
											   $uid = $array['uid'];
												
											  
												}
							 $stmt->close();
							 
							 
							 //translate uid to phone number
							 $match = "SELECT * FROM users WHERE uid = ?";
							 $stmt = $conn->prepare($match);
							 $stmt->bind_param('s', $uid);
							 if($stmt->execute()){$result = $stmt->get_result();
												 $array = $result->fetch_assoc();
												  $phone_number = $array['phone_number'];
												 }
							 
							 
							 echo $uid; echo $phone_number;
							 
							 
							 //insert
							 
							 $insert = "INSERT INTO message_log (id, mid, locatorid, uid, petid, body, time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
							 $stmt = $conn->prepare($insert);
							 $stmt->bind_param('issssssi', $mxid, $mid, $from, $uid, $piece[1], $body, $time, $non);
							 $stmt->execute();
							 $stmt->close();
						
							 
							 
							 
							 
							 
						 }
						 
						 
						 echo
							 "
						 <Response>
						 <Message to='+1'>
						 </Message>
						 </Response>
						 
						 ";
						 
						 }
						 
					 }else{
						 
						 ///////////////////////////////////////////////////////////////////////////////////////////
						 
						 //array holding data
					$array = $result->fetch_assoc();
					 
						 //user id if number belongs to registered user
					 $cuid = $array['uid'];
					}
					 
					 //$stmt->close();
					 //
$cidd = "SELECT * FROM message_log WHERE uid = ?";
$stmt = $conn->prepare($cidd);
$stmt->bind_param('s', $cuid);
if($stmt->execute()){$result = $stmt->get_result();
					
					 
					 $rows = $result->num_rows;
					 
					 
					
					 if($rows == 0){$locatorid = null;}else{
						 $array = $result->fetch_assoc();
					 $locatorid = $array['locatorid'];
					}
					
					}
$stmt->close();

					 
					}







?>