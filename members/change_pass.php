<?php

//Starting mysqli connection
require('../connection.php');

//Starting user session
session_start();


//Directing to login if not logged in
if($_SESSION['uid'] == ""){header("location: index.php");}


//Key to allow for update to occur / prevents blank fields from submitting
if(strip_tags($_POST['secu'] == "pach")){

//Checking for empty fields / Setting error var if found / redirecting back to settings on error
if(empty(strip_tags($_POST['passone'])) || empty(strip_tags($_POST['passtwo']))){$_SESSION['passworderror'] = "Password is empty"; header("location: settings.php");}


//Checking if password fields contains at least 6 chars / setting error var if length is long enough/ redirecting to settings page on error
if(strlen(strip_tags($_POST['passone'])) < 6){$_SESSION['passworderror'] = "New assword is less than 6 characters"; header("location: settings.php"); exit;}
if(strlen(strip_tags($_POST['passtwo'])) < 6){$_SESSION['passworderror'] = "New password is less than 6 characters"; header("location: settings.php"); exit;}
	
	//If both password fields are valid the password var will be encrypted and used as new password
if(strip_tags($_POST['passone'] == strip_tags($_POST['passtwo']))){$password = sha1(strip_tags($_POST['passone']));}
	

	//Updating row with new password
$updatepassword = "UPDATE users SET password = ? WHERE uid = ?";
$stmt = $conn->prepare($updatepassword);
$stmt->bind_param('ss', $password, $_SESSION['uid']);
if($stmt->execute()){
	//Var containing successful update status / redirecting to settings after completion
	$_SESSION['passworderror'] = "Password was updated successfully."; header("location: settings.php"); exit;}else{
	//Var containing update error / redirecting to settings 
	$_SESSION['passworderror'] = "Password unable to be changed."; header("location: settings.php"); exit;}
$stmt->close();

}


?>