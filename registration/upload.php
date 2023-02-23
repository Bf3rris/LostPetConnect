<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Setting variable to use ....[]
$key = strip_tags($_POST['key']);


//Query for photo directory
$configid = "1";
$dir_sql = "SELECT photo_dir FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($dir_sql);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $photo_dir = $array['photo_dir'];
					
					}
$stmt->close();
	
//If pet id is empty one will be newly generated
$_SESSION['petid'] = substr(str_shuffle(md5(date('his'))), 0, 8);


//Allowed image/MIME types
$allowed_types = array("image/gif", "image/jpg", "image/jpeg", "image/png");


if($_FILES["photo"]["tmp_name"] == null){$_SESSION['nofile'] = "<font color='#ff0000'><strong>No image file was submitted.</strong></font>";header("location: upload_photo.php"); exit;}else{$contents = fopen($_FILES["photo"]["tmp_name"], 'r');}


$mime_type = mime_content_type($_FILES["photo"]["tmp_name"]);
if(in_array($mime_type, $allowed_types)){
if($contents = fopen($_FILES["photo"]["tmp_name"], 'r')){
$path = basename($_FILES["photo"]["name"]);





//...uploaded user image...[]
$contents = fopen($_FILES["photo"]["tmp_name"], 'r');
$path = basename($_FILES["photo"]["name"]);







//Setting var as mime type / used for filename creation and...[]
$mime = explode(".", $path);

//

//Setting filename to store uploaded image of pet
$filename = $_SESSION['petid'].".".$mime[1];

//////////////////////////////////////////

//Storing uploaded image
file_put_contents("../".$photo_dir.$filename, $contents);


//
if($key == "photo"){
	
	$_SESSION['regx'] = "yes";
	
	//Session var containing image location to view on final registration page
	$_SESSION['img'] = "../".$photo_dir.$filename;
	
	//Session var set so registration can complete
	$_SESSION['complete'] = "cl";
	
	//Redirecting to final step to view image & complete registration
header("location: upload_photo.php");
}}}else{$_SESSION['invalidfile'] = "<font color='#ff0000'><strong>You have uploaded an invalid file.<br />Try again.</strong></font>"; header("location: upload_photo.php");}
?>