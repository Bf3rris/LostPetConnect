<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Flag / var allows image upload to complete and user to be directed
//back to proper page after image upload
$key = strip_tags($_POST['key']);
	

//Generating pet id for newly created pet
$_SESSION['petid'] = substr(str_shuffle(md5(date('his'))), 0, 8);


//Allowed image/MIME types
$allowed_types = array("image/gif", "image/jpg", "image/jpeg", "image/png");

//If user uploads no image / error message / redirect to re-try
if($_FILES["photo"]["tmp_name"] == null){$_SESSION['nofile'] = "<font color='#ff0000'><strong>No image file was submitted.</strong></font>";header("location: upload_photo.php"); exit;}else{$contents = fopen($_FILES["photo"]["tmp_name"], 'r');}

//Verifying MIME type of user uploaded image is allowed
$mime_type = mime_content_type($_FILES["photo"]["tmp_name"]);
if(in_array($mime_type, $allowed_types)){
if($contents = fopen($_FILES["photo"]["tmp_name"], 'r')){
$path = basename($_FILES["photo"]["name"]);


//User uploaded image
$contents = fopen($_FILES["photo"]["tmp_name"], 'r');
$path = basename($_FILES["photo"]["name"]);



//Setting var as mime type / used for file creation
$mime = explode(".", $path);


//Setting filename to store uploaded image of pet
$filename = $_SESSION['petid'].".".$mime[1];


//Storing uploaded image
file_put_contents("../images/images/".$filename, $contents);


//Var previously set to allow redirection upon successful upload
if($key == "photo"){
	
	$_SESSION['regx'] = "yes";
	
	//Session var containing image location to view on final registration page
	$_SESSION['img'] = $filename;
	
	//Session var set so registration can complete
	$_SESSION['complete'] = "cl";
	
	//Redirecting to final step to view image & complete registration
header("location: upload_photo.php");
}}else{
	//If an invalid file type/photo is submitted / redirect to re-try
	$_SESSION['invalidfile'] = "<font color='#ff0000'><strong>You have uploaded an invalid file.<br />Try again.</strong></font>"; header("location: upload_photo.php");}}
?>