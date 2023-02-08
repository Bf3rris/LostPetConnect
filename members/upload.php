<?php

require('../connection.php');
session_start();


//Directing to login if not logged in
if($_SESSION['uid'] == ""){header("location: index.php");}

//Setting photo directory
$dir = "../images/images/";

//Used to identify referring page
$key = strip_tags($_POST['key']);

//Generating petid if there is none from previous interaction


//Opening photo file
$contents = fopen($_FILES["photo"]["tmp_name"], 'r');


$path = basename($_FILES["photo"]["name"]);

//Getteing MIME type and preparing for use in filename
$mime = explode(".", $path);

//if uploading picture while adding new pet
if($key == "aap"){
	
	//
if(strip_tags($_POST['id']) == ""){
	
		//Generating new petid
		if($petid == ""){$petid = substr(str_shuffle(md5(date('his'))), 0, 8);}
}
	
	//Directory where QR codes are stored
	$qrdir = "../images/qr/";
	
	//QR code filename
	$qrfilename = $petid."-qr.png";
	
	//Domain to use when generating QR codes
	$domain = "http://13.59.192.46/";
	
	//Generating QR code using Google Devs/Charts
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."view/petid=".$petid);
	
	//Storing QR code
	file_put_contents($qrdir.$qrfilename, $data);

	
	//Setting filename to use with image of pet
	$photofilename = $petid.".".$mime[1];
	
	
	//Storing pets photo
	file_put_contents($dir.$photofilename, $contents);
	
	//Creating var to store image locaation in database
	$dbfn = "images/images/".$photofilename;
	
	$placeholder = "";
	
	
	
	//Add to database for draft purposes
	$draft = "INSERT INTO pets (pid, uid, name, age, description, image, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($draft);
	$stmt->bind_param('sssssss', $petid, $_SESSION['uid'], $placeholder, $placeholder, $placeholder, $dbfn, $placeholder);
	$stmt->execute();

    $stmt->close();
	$_SESSION['imgplc'] = $dir.$photofilename;
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
	$dbfn = "images/images/".$photofilename;
	
	
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
	if($oldimage != "images/images/default_pic.png"){unlink("../".$oldimage);}

	//Setting filename to store new pohoto - *filename stays same (petid) / mime type may change
	//$filename = $petid.".".$mime[1];
	

	//Storing photo
	file_put_contents($dir.$photofilename, $contents);
	



$update = "UPDATE pets SET image = ? WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param('sss', $dbfn, $petid, $_SESSION['uid']);
if($stmt->execute()){$_SESSION['uploadstatus'] = "Image has been updated";}else{$_SESSION['uploadstatus'] = "There was a problem";}
$stmt->close();
}
if($key == "aap"){$location = "add_pet.php";}else{$location = "manage_pet.php?petid=$petid";}
header("location: $location");
exit;
?>