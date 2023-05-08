<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Query for site settings
$configid = "1";
$dir = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($dir);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Setting domain var for use url in QR code creation
					 $domain = $array['domain'];
					 
					 //Setting photo dir var for storing pet photo
					 $photo_dir = $array['photo_dir'];
					 
					 //Setting qr code dir for storing QR code
					 $qr_dir = $array['qr_dir'];
					}
$stmt->close();


//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}

//Setting photo directory
$dir = "../".$photo_dir;

//Used to identify referring page (manage pet / add pet)
$key = strip_tags($_POST['key']);


//Detecing if file was submitted / Setting error message / redirect if not
if($_FILES["photo"]["tmp_name"] == null){$_SESSION['nofile'] = "<font color='#ff0000'><strong>No image file was submitted.</strong></font>";
										 
										 
										 //Directing to proper page / 'add pet' or 'manage pet'
										if(strip_tags($_POST['ref']) == "pr"){
										 header("location: manage_pet.php?petid=$_POST[petid]"); exit;
											
										}else{header("location: add_pet.php");}
										
										}else{$contents = fopen($_FILES["photo"]["tmp_name"], 'r');}

//Allowed image types
$allowed_types = array("image/gif", "image/jpg", "image/jpeg", "image/png");

//Checking mime type of uploaded image
$mime_type = mime_content_type($_FILES["photo"]["tmp_name"]);

//Verifying if uploaded file is allowed
if(in_array($mime_type, $allowed_types)){
if($contents = fopen($_FILES["photo"]["tmp_name"], 'r')){
$path = basename($_FILES["photo"]["name"]);

//Getting MIME type and preparing for use in filename
$mime = explode(".", $path);

//if uploading photo while adding new pet
if($key == "aap"){
	
	//Checking if pet id exists / creating new pet id if not
if(isset($_POST['id'])){}else{
	
		//Generating new petid
		if(isset($petid)){}else{$petid = substr(str_shuffle(md5(date('his'))), 0, 8);}
}
	

	
	//QR code filename
	$qrfilename = $petid."-qr.png";
	

	//Generating QR code using Google Devs/Charts
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."/view/petid=".$petid);
	
	//Storing QR code
	file_put_contents("../".$qr_dir.$qrfilename, $data);

	
	//Setting filename to use with image of pet
	$photofilename = $petid.".".$mime[1];
	
	
	//Storing pets photo
	file_put_contents("../".$photo_dir.$photofilename, $contents);
	
	//Creating var to store image locaation in database
	$dbfn = $photo_dir.$photofilename;
	
	//Inputs that are completed by the user later
	$placeholder = "";
	
	
	
	
	//Adding pet to database upon successful photo upload / creating draft
	$draft = "INSERT INTO pets (pid, uid, name, age, timenoun, description, image, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($draft);
	$stmt->bind_param('ssssssss', $petid, $_SESSION['uid'], $placeholder, $placeholder, $placeholder, $placeholder, $dbfn, $placeholder);
	$stmt->execute();

    $stmt->close();
	
	//Used to display uploaded image on add pet page
	$_SESSION['imgplc'] = $photo_dir.$photofilename;
	
	//Saving session var for use on 'add pet' page
    $_SESSION['pid'] = $petid;

	//Redirecting to manage pets page to complete pet details
	header("location: manage_pet.php?petid=$petid");

	exit;
}else{
	
	
	

	//Setting petid var for use in file creation and for use in sql query
	$petid = strip_tags($_REQUEST['petid']);
	
	//Setting filename to store image of pet
	$photofilename = $petid.".".$mime[1];
	
	//Creating var to set image location in DB
	$dbfn = $photo_dir.$photofilename;
	
	
	//Retrieving photo name and location from database to remove previous pet image
$replace = "SELECT image FROM pets WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($replace);
$stmt->bind_param('ss', $petid, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
					 
					 //Setting var containing location of previous image
					 $oldimage = $data['image'];
					
			}
	$stmt->close();
	
	//Deleting previous image file from directory / Prevents default photo from being deleted if in use by interacting pet
	if($oldimage != $photo_dir."default_pic.png"){unlink("../".$oldimage);}

	

	//Storing photo of pet
	file_put_contents("../".$photo_dir.$photofilename, $contents);
	


//Updating row with new image 
$update = "UPDATE pets SET image = ? WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param('sss', $dbfn, $petid, $_SESSION['uid']);
if($stmt->execute()){$_SESSION['uploadstatus'] = "Image has been updated";}else{$_SESSION['uploadstatus'] = "There was a problem";}
$stmt->close();
}
	
	//Redirecting back to proper page after upload
if($key == "aap"){$location = "add_pet.php";}else{$location = "manage_pet.php?petid=$petid";}
header("location: $location");
exit;}}else{
	
	//Redirecting to proper page after 
	if($key == "aap"){$location = "add_pet.php";}else{$location = "manage_pet.php?petid=$_POST[petid]";}
	
	//Setting session var for invalid file error message
	$_SESSION['invalidfile'] = "<font color='#ff0000'><strong>You have uploaded an invalid file.</strong></font>";  if($key == "aap"){header("location: add_pet.php");}else{header("location: manage_pet.php?petid=$_POST[petid]");}}
?>