<?php

require('../connection.php');
session_start();


$configid = "1";
$dir = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($dir);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $domain = $array['domain'];
					 $photo_dir = $array['photo_dir'];
					 $qr_dir = $array['qr_dir'];
					}
$stmt->close();


//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}

//Setting photo directory
$dir = "../".$photo_dir;

//Used to identify referring page
$key = strip_tags($_POST['key']);

//Generating petid if there is none from previous interaction


if($_FILES["photo"]["tmp_name"] == null){$_SESSION['nofile'] = "<font color='#ff0000'><strong>No image file was submitted.</strong></font>";header("location: manage_pet.php?petid=$_POST[petid]"); exit;}else{$contents = fopen($_FILES["photo"]["tmp_name"], 'r');}
//Opening photo file


$allowed_types = array("image/gif", "image/jpg", "image/jpeg", "image/png");

$mime_type = mime_content_type($_FILES["photo"]["tmp_name"]);
if(in_array($mime_type, $allowed_types)){
if($contents = fopen($_FILES["photo"]["tmp_name"], 'r')){
$path = basename($_FILES["photo"]["name"]);

//Getting MIME type and preparing for use in filename
$mime = explode(".", $path);

//if uploading picture while adding new pet
if($key == "aap"){
	
	//
if(isset($_POST['id'])){}else{
	
		//Generating new petid
		if(isset($petid)){}else{$petid = substr(str_shuffle(md5(date('his'))), 0, 8);}
}
	

	
	//QR code filename
	$qrfilename = $petid."-qr.png";
	

	
	//Generating QR code using Google Devs/Charts
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."view/petid=".$petid);
	
	//Storing QR code
	file_put_contents("../".$qr_dir.$qrfilename, $data);

	
	//Setting filename to use with image of pet
	$photofilename = $petid.".".$mime[1];
	
	
	//Storing pets photo
	file_put_contents("../".$photo_dir.$photofilename, $contents);
	
	//Creating var to store image locaation in database
	$dbfn = $photo_dir.$photofilename;
	
	$placeholder = "";
	
	
	
	//Add to database for draft purposes
	$draft = "INSERT INTO pets (pid, uid, name, age, description, image, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($draft);
	$stmt->bind_param('sssssss', $petid, $_SESSION['uid'], $placeholder, $placeholder, $placeholder, $dbfn, $placeholder);
	$stmt->execute();

    $stmt->close();
	$_SESSION['imgplc'] = $photo_dir.$photofilename;
		$_SESSION['pid'] = $petid;
//$absolute = "../images/images/".$filename;
//file_put_contents($absolute);

	header("location: manage_pet.php?petid=$petid");

	exit;
}else{
	
	
	

	//
	$petid = strip_tags($_REQUEST['petid']);
	
	//Setting filename to use with image of pet
	$photofilename = $petid.".".$mime[1];
	
	

		//Creating var to store image locaation in database
	$dbfn = $photo_dir.$photofilename;
	
	
	//Retrieving photo name and location
$replace = "SELECT image FROM pets WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($replace);
$stmt->bind_param('ss', $petid, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
					 $oldimage = $data['image'];
					
			}
	$stmt->close();
	//Deleting previous image file from directory / Prevents default photo from being deleted
	if($oldimage != $photo_dir."default_pic.png"){unlink("../".$oldimage);}

	//Setting filename to store new pohoto - *filename stays same (petid) / mime type may change
	//$filename = $petid.".".$mime[1];
	

	//Storing photo
	file_put_contents("../".$photo_dir.$photofilename, $contents);
	



$update = "UPDATE pets SET image = ? WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param('sss', $dbfn, $petid, $_SESSION['uid']);
if($stmt->execute()){$_SESSION['uploadstatus'] = "Image has been updated";}else{$_SESSION['uploadstatus'] = "There was a problem";}
$stmt->close();
}
if($key == "aap"){$location = "add_pet.php";}else{$location = "manage_pet.php?petid=$petid";}
header("location: $location");
exit;}}else{
	
	if($key == "aap"){$location = "add_pet.php";}else{$location = "manage_pet.php?petid=$petid";}
	
	$_SESSION['invalidfile'] = "<font color='#ff0000'><strong>You have uploaded an invalid file.</strong></font>";  if($key == "aap"){header("location: add_pet.php");}else{header("location: manage_pet.php?petid=$_POST[petid]");}}
?>