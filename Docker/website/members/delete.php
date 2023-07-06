<?php


//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();




//Retrieving communication method to match communication method - 0 = call 1 = text message
if(isset($_REQUEST['id'])){$id = strip_tags($_REQUEST['id']); $count = 0;}else{if(isset($_REQUEST['mid'])){$id = strip_tags($_REQUEST['mid']); $count = 1;}}


//If data pertains to call log 
if($count == 0){
	
	
//Delete row from call log
$delete = "DELETE FROM call_log WHERE id = ? AND uid = ?";
$stmt = $conn->prepare($delete);
$stmt->bind_param('is', $id, $_SESSION['uid']);
$stmt->execute();
$stmt->close();
	
	//Directing logged in user to notifications page
header("location: notifications.php");
	exit;

	
	
	
}else{

	//Else action deletes data from message log
//Delete data row
$delete = "DELETE FROM message_log WHERE id = ? AND uid = ?";
$stmt = $conn->prepare($delete);
$stmt->bind_param('is', $id, $_SESSION['uid']);
$stmt->execute();
$stmt->close();
	
		//Directing logged in user to notifications page
	header("location: notifications.php");
	exit;
}
?>