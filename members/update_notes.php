<?php

require('../connection.php');

session_start();

//
if(isset($_REQUEST['id'])){$id = strip_tags($_REQUEST['id']); $count = 0;}else{if(isset($_REQUEST['mid'])){$id = strip_tags($_REQUEST['mid']); $count = 1;}}






if(isset($_POST['update'])){
	
	
  $nup = strip_tags($_POST['notes']);

if($_REQUEST['method'] == 0){
	
	$usql = "UPDATE call_log SET notes = ? WHERE id = ? AND uid = ?";
	$stmt = $conn->prepare($usql);
	$stmt->bind_param('sis', $nup, $_POST['vid'], $_SESSION['uid']);
	$stmt->execute();
	$stmt->close();
	$_SESSION['update'] = "update";
	header("location: notes.php?id=$_POST[vid]");
}else{
	
	$usql = "UPDATE message_log SET notes = ? WHERE id = ? AND uid = ?";
	$stmt = $conn->prepare($usql);
	$stmt->bind_param('sis', $nup, $_POST['vid'], $_SESSION['uid']);
	$stmt->execute();
	$stmt->close();
		$_SESSION['update'] = "update";

	header("location: notes.php?mid=$_POST[vid]");

}

}


?>