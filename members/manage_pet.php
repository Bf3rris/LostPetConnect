<?php

//Start mysqli connection
require('../connection.php');

//Start user session
session_start();

//Directing to login if not logged in
if($_SESSION['uid'] == ""){header("location: index.php");}


//Requesting petid from pet list page and or from form manage pets hidden input 
$petid = strip_tags($_REQUEST['petid']);

//Checking if submission is from form
if($_POST['form'] == "formdata"){

	//Posting / sanitizing input fields of pet details
    $nameupdate = strip_tags($_POST['name']);
	$ageupdate = strip_tags($_POST['age']);
	$genderupdate = strip_tags($_POST['gender']);
	$descriptionupdate = strip_tags($_POST['description']);

//Checking for empty pet details fields
	if(empty($nameupdate) || empty($ageupdate) || empty($genderupdate) || empty($descriptionupdate)){
		
		
		//Setting var holding error fields error
		$_SESSION['emptydetails'] = "Empty fields are not allowed.";
		
		//Redirecting to manage pets page if fields are blank
		header("manage_pet.php");}
	
	//Updating pet details
	$update = "UPDATE pets SET name = ?, age = ?, description = ?, gender = ? WHERE pid = ? AND uid = ?";
	$stmt = $conn->prepare($update);
	$stmt->bind_param('ssssss', $nameupdate, $ageupdate, $descriptionupdate, $genderupdate, $petid, $_SESSION['uid']);
	if($stmt->execute()){$_SESSION['updatestatus'] = "<Font color='green'>Pet information has been updated</font>";}else{$_SESSION['updatestatus'] = "<font color='red'>something went wrong</font>";}
    $stmt->close();
	
	//Adding header to prevent blank info
	//header("location: manage_pet.php?petid=$petid");
	//exit;
}


//requesting pet details
$request = "SELECT * FROM pets WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($request);
$stmt->bind_param('ss', $petid, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
				
					 //Turning pet details into separate variables
					 $name = $data['name'];
					 $gender = $data['gender'];
					 $age = $data['age'];
					 $description = $data['description'];
					 $image = $data['image'];
					}
$stmt->close();






?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lost Pet Connect - Manage Pet</title>
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
      <td valign="top">
		
		<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="126">&nbsp;</td>
      <td width="712">&nbsp;</td>
      <td width="62">&nbsp;</td>
    </tr>
    <tr>
      <td height="50">&nbsp;</td>
      <td align="center"><h2>Lost Pet Connect</h2></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="50">&nbsp;</td>
      <td align="center"><h3>Manage Pet</h3>
		
		  <?php
		  //Displaying and unsetting if update of information
		  if($_SESSION['updatestatus'] != ""){echo $_SESSION['updatestatus'];
											  
											   unset($_SESSION['updatestatus']);
											  
											 }
		  //Upload status message / photo update
		  if($_SESSION['uploadstatus'] != ""){echo $_SESSION['uploadstatus'];
											  //Unsetting error message
											   unset($_SESSION['uploadstatus']);
											  
											 }
		  //Empty fields status message / pet details update
		 if($_SESSION['emptydetails'] != ""){echo $_SESSION['emptydetails'];
											
											//Unsetting error message
											 unset($_SESSION['emptydetails']);
											}
											  
											 
		  
		  ?>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php
		  
		  //Navigation menu
		  require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">

		
		  
		  
		  <table width="600" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		  
		  <!---------------Start of photo upload form---------------->
		<form action="upload.php" method="post" enctype="multipart/form-data">
		<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>
	  <td align="center">
		  Current Photo
		  
		  </td>
	  <td align="center">&nbsp;</td>
	  <td align="center">QR Code</td>
	  </tr>
    <tr>      <td width="180" rowspan="5" align="center"><img src="../<?php echo $image; ?>" width="150" height="150"></td>
      <td width="255">&nbsp;</td>
      <td width="165" align="center"><small><a href="#">Print</a></small></td>
    </tr>
    <tr>
      <td><input type="file" name="photo"></td>
      <td rowspan="3" align="center"><img src="../images/qr/<?php
		  //Displaying QR code 
		  echo $petid; ?>-qr.png"><input type="hidden" name="key" value="photo"></td>
    </tr>
    <tr>
      <td><input type="hidden" name="petid" value="<?php
		  //Used as key updates on form submission
		  echo $petid; ?>"></td>
      </tr>
    <tr>
      <td><input type="submit" value="Upload Photo"></td>
      </tr>
    <tr>
      <td><input type="hidden" name="key" value="photo"></td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
			  </form>
		 <!---------------End of photo upload form---------------->
		
		</td>
    </tr>
  </tbody>
</table>

		  
		  <p></p>
		  
		
		  
		  <table width="600" border="1" cellpadding="0" cellspacing="0" bordercolor="lightgray" bordercellspacing="0">
  <tbody>
    <tr>
      <td>
		
				 <!---------------Start of pet details form---------------->
<form action="manage_pet.php" method="post">
		  
		  <table width="600" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="122">Name</td>
      <td width="10">&nbsp;</td>
      <td width="199"><input type="text" name="name" value="<?php echo $name; ?>"></td>
      <td width="53">&nbsp;</td>
      <td width="53">&nbsp;</td>
      <td width="53">&nbsp;</td>
      <td width="53">&nbsp;</td>
      <td width="57">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Gender</td>
      <td>&nbsp;</td>
      <td>
		  
		<select name="gender">
		  <option value="default"><?php echo $gender; ?></option>
			<option value="male">Male</option>
			<option value="female">Female</option>
			</select>
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Age</td>
      <td>&nbsp;</td>
      <td><input type="number" name="age" value="<?php echo $age; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Description</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="5" rowspan="4">
		
		<textarea name="description" cols="50" rows="15"><?php echo $description; ?></textarea>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="hidden" name="form" value="formdata"></td>
      <td><input type="hidden" name="petid" value="<?php
		  
		  //Var used as key to perform update with correct pet
		  echo $petid; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="Update Pet"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
		  </form>
		   <!---------------End of pet details form---------------->
		
		
		</td>
    </tr>
  </tbody>
</table>

		  
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="200">&nbsp;</td>
      <td width="300">&nbsp;</td>
      <td width="100"><a href="remove_pet.php?petid=<?php echo $petid; ?>&action=remove">Remove Pet</a></td>
    </tr>
  </tbody>
</table>

		  
		</td>
    </tr>
    <tr>
      <td height="299" align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>

	
		
		</td>
      <td height="990">&nbsp;</td>
    </tr>
    </tbody>
</table>

		
		</td>
    </tr>
  </tbody>
</table>

	
	

</body>
</html>