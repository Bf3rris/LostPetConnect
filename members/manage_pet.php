<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


//Requesting petid from pet list page and or from form manage pets hidden input 
$petid = strip_tags($_REQUEST['petid']);

//Checking if submission is from form
if(isset($_POST['formref'])){$key = "mp";}else{$key = "unset";}
if($key == "mp"){

	//Posting / sanitizing input fields of pet details
    $nameupdate = strip_tags($_POST['name']);
	$ageupdate = strip_tags($_POST['age']);
	$genderupdate = strip_tags($_POST['gender']);
	$descriptionupdate = strip_tags($_POST['description']);
	$statusupdate = strip_tags($_POST['status']);
	
	if($statusupdate == "1"){$status_date = "";}else{$status_date = date('m.d.y');}
	
//Checking for empty pet details fields
	if(empty($nameupdate) || empty($ageupdate) || empty($genderupdate) || empty($descriptionupdate)){
		
		
		//Setting var holding error fields error
		$_SESSION['emptydetails'] = "<font color='#ff0000'><strong>Empty fields are not allowed.</strong></font>";
		
		//Redirecting to manage pets page if fields are blank
		header("manage_pet.php");}
	
	//Updating pet details
	$update = "UPDATE pets SET name = ?, age = ?, description = ?, gender = ?, status = ?, status_date = ? WHERE pid = ? AND uid = ?";
	$stmt = $conn->prepare($update);
	$stmt->bind_param('ssssssss', $nameupdate, $ageupdate, $descriptionupdate, $genderupdate, $statusupdate, $status_date, $petid, $_SESSION['uid']);
	if($stmt->execute()){$_SESSION['updatestatus'] = "<font color='green'><strong>Pet information has been updated.</strong></font>";}else{$_SESSION['updatestatus'] = "<font color='red'>something went wrong</font>";}
    $stmt->close();
	

}

//SQL query for pet details
$request = "SELECT * FROM pets WHERE pid = ? AND uid = ?";
$stmt = $conn->prepare($request);
$stmt->bind_param('ss', $petid, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
				
					 //Setting vars for each pet detail
					 $name = $data['name'];
					 $gender = $data['gender'];
					 $age = $data['age'];
					 $description = $data['description'];
					 $image = $data['image'];
					 $status = $data['status'];
					 $status_date = $data['status_date'];
					}
$stmt->close();


//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Manage Pet</title>
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
		  if(isset($_SESSION['updatestatus'])){echo $_SESSION['updatestatus'];
											  
											   unset($_SESSION['updatestatus']);
											  
											 }
		  //Upload status message / photo update
		  if(isset($_SESSION['uploadstatus'])){echo $_SESSION['uploadstatus'];
											  //Unsetting error message
											   unset($_SESSION['uploadstatus']);
											  
											 }
		  //Empty fields status message / pet details update
		 if(isset($_SESSION['emptydetails'])){echo $_SESSION['emptydetails'];
											
											//Unsetting error message
											 unset($_SESSION['emptydetails']);
											}
		  
		  //Empty upload status message
		  	 if(isset($_SESSION['nofile'])){echo $_SESSION['nofile'];
											
											//Unsetting error message
											 unset($_SESSION['nofile']);
											}
		  
		  if(isset($_SESSION['invalidfile'])){echo $_SESSION['invalidfile']; unset($_SESSION['invalidfile']);}
		  						?>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="18">&nbsp;</td>
      <td align="center">&nbsp;</td>
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
    <tr>      <td width="180" rowspan="5" align="center"><img src="../<?php echo $image; ?>" width="150" height="150" border="1"></td>
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
		  <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>
			<option value="<?php if($gender == "Male"){echo "Female";}else{echo "Male";}?>"><?php if($gender == "Female"){echo "Male";}else{echo "Female";}?></option>
			
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
      <td>Missing?</td>
      <td>&nbsp;</td>
      <td><select name="status">
		  <option value="<?php echo $status; ?>">
			  <?php
			  if($status == "1"){echo "No";}else{echo "Missing!";}
			  ?>
			  </option>
		  <option value="1">No</option>
		  <option value="2">Yes</option>
		  </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td> <?php if($status == "2"){echo "Marked as missing on: $status_date";} ?></td>
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
      <td><input type="hidden" name="formref" value="mp"></td>
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