<?php

require('../connection.php');

session_start();



if(isset($_POST['block'])){
	
	
	$confirm = strip_tags($_POST['block']);

	
	
	//retrieve restricted list
	$rr = "SELECT restricted FROM users WHERE uid = ?";
	$stmt = $conn->prepare($rr);
	$stmt->bind_param('s', $_SESSION['uid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 $restricted_list = $array['restricted'];
						}
	
	$stmt->close();

	//append restricted list
	$new = $restricted_list.$_SESSION['block_number'].",";
	
	//update restricted column
	$usql = "UPDATE users SET restricted = ? WHERE uid = ?";
	$stmt = $conn->prepare($usql);
	$stmt->bind_param('ss', $new, $_SESSION['uid']);
	$stmt->execute();
	$stmt->close();
	header("location: notifications.php");
	unset($_SESSION['block_number']);
	
$_SESSION['restriction'] = "<font color='green'><strong>Restriction list has been updated.</strong></font>"; header("location: settings.php"); exit;
header("location: settings.php");
}else{

$update = strip_tags($_REQUEST['restricted_list']);

if(isset($_POST['submit'])){

$run_update = "UPDATE users SET restricted = ? WHERE uid = ?";
$stmt = $conn->prepare($run_update);
$stmt->bind_param('ss', $update, $_SESSION['uid']);
$stmt->execute();
$stmt->close();
	unset($_SESSION['block_number']);

$_SESSION['restriction'] = "<font color='green'><strong>Restriction list has been updated.</strong></font>"; header("location: settings.php"); exit;
}

}
?>