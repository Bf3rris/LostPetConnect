<?php

//Start mysqli connection
require('../connection.php');

//Start usersession
session_start();



//Redirecting to login if user isn't logged in
if($_SESSION['uid'] == ""){header("location: index.php");}

//Key is set in form to allow for input fields to be submitted / prevents unnecessary submissions
if(strip_tags($_POST['key'] == "aap")){
	//
if(strip_tags($_POST['petid'] == "")){


	//Posting / sanitizing vars to input new pet
    $name = strip_tags($_POST['name']);
	$age = strip_tags($_POST['age']);
	$gender = strip_tags($_POST['gender']);
	$description = strip_tags($_POST['description']);
	
	//Default pet image until user uploads own
	$default_image = "images/images/default_pic.png";
	
	//Generating petid
	$pid = substr(str_shuffle(md5(date('his'))), 0, 8);

	
	//Inserting new pet details in table
	$update = "INSERT INTO pets (pid, uid, name, age, description, image, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($update);
	$stmt->bind_param('sssssss', $pid, $_SESSION['uid'], $name, $age, $description, $default_image, $gender);
	if($stmt->execute()){
		//Holds success message for successful adding of pet to table
		$_SESSION['createstatus'] = "<font color='green'>$name was added to your pets</font>";
						 
						
						
						
	//Directory QR codes are stored to
	$dir = "../images/qr/";
						 
	//Filename of QR code
	$filename = $pid."-qr.png";
						 
						 
	//Domain or IP that hosts the webpage
	$domain = "http://13.59.192.46";
						 
	//Call to Google Developers to generate QR code
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."/view/petid=".$pid);
						 
    //Storing QR code
	file_put_contents($dir.$filename, $data);
						
		//Redirecting to 'manage pets' after successful addition of pet to table
	header("location: manage_pet.php?petid=$pid");
	
						}
}
}elseif(
	//Allows for pet details to be edited if pet already exists
	strip_tags($_POST['petid'] != "")){
	
	$currentid = strip_tags($_POST['petid']);
	
	//Posting / sanitizing vars containing pet details
    $name = strip_tags($_POST['name']);
	$age = strip_tags($_POST['age']);
	$gender = strip_tags($_POST['gender']);
	$description = strip_tags($_POST['description']);
		

	//Updating pet details
	$uupdate = "UPDATE pets SET name = ?, age = ?, description = ?, gender = ? WHERE pid = ? AND uid = ?";
	$stmt = $conn->prepare($uupdate);
    $stmt->bind_param('ssssss', $name, $age, $description, $gender, $currentid, $_SESSION['uid']);
	
	if($stmt->execute()){
		
		//Holds success message for successful update of pet details
		$_SESSION['petupdated'] = "<font color='green'>Pet has been updated</font>";
						
						}
	$stmt->close();
	
	
	
}else{}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lost Pet Connect - Add a New Pet</title>
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
      <td align="center"><h3>Add new Pet</h3>
		
		  <?php
		  //Displayed on successful update
		  if($_SESSION['createstatus'] != ""){
			  /
			  echo $_SESSION['createstatus'];
											  //Unsetting update success message
											   unset($_SESSION['createstatus']);
											  
											 }
  ?>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">

		
		  
		  
		  <table width="600" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		  
		  <!----------Form for photo upload starts here---------->
		<form action="upload.php" method="post" enctype="multipart/form-data">
		<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>
	  <td align="center">
		 Photo
		  
		  </td><td></td>
		  <td align="center">&nbsp;</td>
	  </tr>
    <tr>
      <td width="180" rowspan="5" align="center">
		  <?php
		  //Displays photo of pet if exists
		  if($_SESSION['imgplc'] != ""){echo "<img src='$_SESSION[imgplc]'>";
									   unset($_SESSION['imgplc']);
									   
									   }else{
			  
			  //Displays default image if user photo doesn't exist
			  echo '
		  <img src="../images/images/default_pic.png" width="150" height="150">';}
		  ?>
			  </td>
      <td width="313">&nbsp;</td>
      <td width="107">&nbsp;</td>
    </tr>
    <tr>
      <td><input type="file" name="photo"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="hidden" name="key" value="aap"></td>
    </tr>
    <tr>
      <td><input type="submit" value="Upload Photo"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
			  </form>
		<!----------Form for photo upload ends here---------->
		
		</td>
    </tr>
  </tbody>
</table>

		  
		  <p></p>
		  
		
		  
		  <table width="600" border="1" cellpadding="0" cellspacing="0" bordercolor="lightgray" bordercellspacing="0">
  <tbody>
    <tr>
      <td>
		
				<!----------Form for pet details starts here---------->
<form action="add_pet.php" method="post" name="form">
		  
		  <table width="600" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="122">Name</td>
      <td width="10">&nbsp;</td>
      <td width="199"><input type="text" name="name"></td>
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
      <td><input type="number" name="age"></td>
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
		
		<textarea name="description" cols="50" rows="15"></textarea>
		
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
      <td><input type="hidden" name="key" value="aap"></td>
      <td><input type="hidden" name="petid" value="<?php
		  
		  //Used to pass pet id to update pet details on submission
		  echo $_SESSION['pid']; unset($_SESSION['pid']); ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><a href="my_pets.php"><input type="button" value="Exit without Saving"></a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="Add pet"></td>
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
		  <!----------Form for pet details ends here---------->
		
		
		</td>
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