<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Redirecting to login if session isn't set
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


//Site settings config ID
$configid = "1";

//Query for site settings
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();


//Setting session var with pet id until removal occurs
if(isset($_SESSION['rmid'])){}else{$_SESSION['rmid'] = strip_tags($_REQUEST['petid']);}


//retrieving photo from db before deletion
	$imgreq = "SELECT name, image FROM pets WHERE pid = ?";
	$stmt = $conn->prepare($imgreq);
	$stmt->bind_param('s', $_SESSION['rmid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$data = $result->fetch_assoc();
						 $photo = $data['image'];
						 $petname = $data['name'];
						}
	$stmt->close();



//Setting var to confirm action for pet removal
if(isset($_POST['confirm'])){$confirm = strip_tags($_POST['confirm']);}else{$confirm = "unset";}

//Function/flag to confirm removal of pet
if($confirm == "Yes"){
	
	

	//Removing all pet data
	//Deleting row associated with pet
	$remove = "DELETE FROM pets WHERE pid = ? AND uid = ?";
	$stmt = $conn->prepare($remove);
	$stmt->bind_param('ss', $_SESSION['rmid'], $_SESSION['uid']);
	if($stmt->execute()){
						
						 //Setting var contaiing status of removal
						 $_SESSION['removestatus'] = "<font color='green'><strong>Pet has been removed</strong></font>";
						 
						 
						 
						 //Deleting photo of pet from images directory
						 //Doing nothing if pets image is default image / prevents default image from being erased
						 if($photo == "images/images/default_pic.png"){}else{unlink("../".$photo);}
							 
							 //Deleting pets QR code from QR code directory
							 unlink("../images/qr/q".$_SESSION['rmid']."-qr.png");
									
							 //Unsetting petid removal var
						unset($_SESSION['rmid']);
		
						//Redirecting user back to 'my pets' details page
						 header("location: my_pets.php");
						 exit;
						
						}else{
		//Displaying error if deletion wasn't completed.
		echo "There was an error. Return to <a href='my_pets.php'>My pets</a>";}
$stmt->close();
	

}



?>



<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Manage Pet / Remove Pet</title>
<style type="text/css">
body,td,th {
	font-family: Arial;
}
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
</style>
</head>

<body>
	
	
	<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td height="990" valign="top">
		
		<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td colspan="3">
		
		  		<?php require('top.php'); ?>

		
		</td>
      </tr>
    <tr>
      <td width="126" height="50">&nbsp;</td>
      <td width="712" align="center"><h2>Lost Pet Connect</h2></td>
      <td width="62">&nbsp;</td>
    </tr>
    <tr>
      <td height="50">&nbsp;</td>
      <td align="center"><h3>Manage Pet / Remove Pet</h3>
		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">

		
		  
		  
		  <table width="450" border="0" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
	  
	  
	  <tr>
	  <td>This will remove your pet from your account and delete all data associated with <strong><i><?php echo $petname; ?></i></strong> including their photo and QR Code.</td>
	  </tr>
    <tr>
      <td align="center">
		  
		 <table width="400" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td> <!------------Start of confirmation form ------------>
		<form action="remove_pet.php" method="post">
		<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>      
	    <td colspan="6">&nbsp;</td>
	    </tr>
    <tr>
      <td colspan="6" align="center"><h2>Continue?</h2></td>
      </tr>
    <tr>
      <td width="29"><input type="hidden" name="complete" value="<?php if(isset($petid)){echo $petid;} ?>"></td>
      <td width="124" align="center"><input type="hidden" name="petid" value="<?php if(isset($petid)){echo $petid;}?>"></td>
      <td width="134" align="center"><input type="submit" name="confirm" value="Yes"></td>
		<td width="135" align="center"><a href="my_pets.php"><input type="button" value="Cancel"></a></td>
      <td width="149">&nbsp;</td>
      <td width="29">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
      </tr>
  </tbody>
</table>
			  </form>
		<!------------End of confirmation form ------------>
		</td>
    </tr>
  </tbody>
</table>

		
		</td>
    </tr>
  </tbody>
</table>

		  
		  <p></p></td>
    </tr>
    <tr>
      <td height="299" align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>

	
		
		</td>
    </tr>
    </tbody>
</table>

		
		</td>
    </tr>
  </tbody>
</table>

	
	

</body>
</html>