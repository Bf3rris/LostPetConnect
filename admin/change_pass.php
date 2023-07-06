<?php

//Starting mysqli connection
require('../connection.php');

//Starting user session
session_start();


//Directing to login if not logged in
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Key to allow for update to occur / prevents blank fields from submitting
if(isset($_POST['formref'])){

//Checking for empty fields / Setting error var if found / redirecting back to settings on error
if(empty(strip_tags($_POST['passone'])) || empty(strip_tags($_POST['passtwo']))){$_SESSION['passworderror'] = "Password is empty"; header("location: admin_settings.php");}


//Checking if password fields contains at least 6 chars / setting error var if length is long enough/ redirecting to settings page on error
if(strlen(strip_tags($_POST['passone'])) < 6){$_SESSION['passworderror'] = "New assword is less than 6 characters"; header("location: admin_settings.php"); exit;}
if(strlen(strip_tags($_POST['passtwo'])) < 6){$_SESSION['passworderror'] = "New password is less than 6 characters"; header("location: admin_settings.php"); exit;}
	
	//If both password fields are valid the password var will be encrypted and used as new password
if(strip_tags($_POST['passone'] == strip_tags($_POST['passtwo']))){$password = sha1(strip_tags($_POST['passone']));}
	

	//Updating row with new password
$updatepassword = "UPDATE admin SET password = ? WHERE id = ?";
$stmt = $conn->prepare($updatepassword);
$stmt->bind_param('ss', $password, $_SESSION['id']);
if($stmt->execute()){
	//Setting var containing successful update status / redirecting to settings after completion
	$_SESSION['passworderror'] = "<font color='green'><strong>Password was updated successfully.</strong></font>"; header("location: admin_settings.php"); exit;}else{
	//Setting var containing update error / redirecting to settings 
	$_SESSION['passworderror'] = "<font color='#ff0000'><strong>Password unable to be changed.</strong></font>"; header("location: admin_settings.php"); exit;}
$stmt->close();

}


?>