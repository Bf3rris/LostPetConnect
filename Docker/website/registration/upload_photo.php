<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();


//$detailkey prevents form from being wiped during page changes
if(isset($_POST['detailkey']) == true){$key = strip_tags($_POST['detailkey']);}else{$key = "unset";}
if($key == "detail"){
	
	//Posting & sanitizing user input
$_SESSION['petname'] = strip_tags($_POST['petname']);
$_SESSION['petage'] = strip_tags($_POST['petage']);
	$_SESSION['timenoun'] = strip_tags($_POST['timenoun']);
$_SESSION['description'] = strip_tags($_POST['description']);
$_SESSION['petgender'] = strip_tags($_POST['gender']);
	
//Checking for empty fields & returning to previous page with error message to complete empty field is detected
if($_SESSION['petname'] == ""){$_SESSION['petnameerror'] = "Pet name can't be empty"; header("location: pet_details.php");}
if($_SESSION['petage'] == ""){$_SESSION['petageerror'] = "Specify your pets age"; header("location: pet_details.php");}
	if($_SESSION['timenoun'] == ""){$_SESSION['timenounerror'] == "Specify months/years"; header(";pcation: pet_details.php");}
if($_SESSION['description'] == ""){$_SESSION['descriptionerror'] = "Enter a description of your pet"; header("location: pet_details.php");}
if($_SESSION['petgender'] == ""){$_SESSION['petgendereerror'] = "You must specify a gender"; header("location: pet_details.php");}

}



//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
						
						//Var holding wesite title
					 $website_title = $array['website_title'];
					 
					 
					}
$stmt->close();

?>


<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Registration / Upload Photo</title>
<style type="text/css">
body,td,th {
	font-family: Arial;
}
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
<for}
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
	
	
	<table width="500" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		<form action="<?php
					  //Session variable set on upload page used to direct user to registration completion page after successful image upload
					  if(isset($_SESSION['regx']) == true){echo "complete.php";}else{echo "upload.php";} ?>" method="post" enctype="multipart/form-data">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="134">&nbsp;</td>
      <td width="405">&nbsp;</td>
      <td width="95">&nbsp;</td>
      </tr>
    <tr>
      <td align="center"></td>
      <td align="center"><h2><?php echo $website_title; ?> </h2></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><h3>Registration</h3></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><strong><u>Upload Photo</u> </strong>Step3 of 3</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"> <?php
		  
		  //Displaying empty file error message if set / unsetting
		  if(isset($_SESSION['nofile'])){echo $_SESSION['nofile']; unset($_SESSION['nofile']);}
			//Displaing invalid file error message if set / unsetting  
		  if(isset($_SESSION['invalidfile'])){echo $_SESSION['invalidfile']; unset($_SESSION['invalidfile']);}
		  
		  ?></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Upload a photo of <strong><?php 
		  //Displaying pet name
		  echo $_SESSION['petname']; ?></strong></td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Adding a photo can help increase identifiability.</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td rowspan="9" align="center">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center"><img src="
		 <?php
		  
		  //Displays default image until user uploads personal photo
		  if(isset($_SESSION['img'])){echo "../images/images/$_SESSION[img]";}else{echo "../images/images/default_pic.png";} ?>
		  " width="250" height="250"></td>
    </tr>
    <tr>
      <td height="45" align="center"><?php if(isset($_SESSION['img'])){echo "<small>You can change this later.</small>";}else{echo "<input type='file' name='photo' id='photo'>";} ?></td>
    </tr>
    <tr>
      <td align="center"><input type="hidden" name="key" value="photo"></td>
    </tr>
    <tr>
      <td align="center">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="28%"><a href="pet_details.php"><input type="button" value="&larr; Previous page"></a></td>
      <td width="49%" align="center">&nbsp;</td>
      <td width="23%">
		  
		  <?php if(isset($_SESSION['img'])){}else{echo"
		  <input type='submit' value='Upload Photo'>";}
		  ?></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td colspan="3" align="center">
		  
		  
		 
		  
		  <?php
		  
		  //Gives user option to uplaod image later or to finish registration upon successful image upload
		  if(isset($_SESSION['img'])){echo "<input type='submit' value='Finish'>";}else{echo 
			  '<a href="complete.php?action=cl"><input type="button" value="Ill do this later"></a>';}
			  ?>
			  
			  </td>
      </tr>
  </tbody>
</table>
			
		
		</td>
    </tr>
  </tbody>
</table>

		
		
		</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    </tbody>
</table>

		  </form>	
		
		
	  </td>
    </tr>
  </tbody>
</table>

	
	
</body>
</html>