<?php

//Starting mysqli connection
require('../connection.php');

//Starting user session
session_start();

//Checking session for logged in status
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Setting session var with pet id until removal occurs
if(isset($_SESSION['rmid'])){}else{$_SESSION['rmid'] = strip_tags($_REQUEST['uid']);}

//retrieving photo from db before deletion
	$imgreq = "SELECT email_address FROM users WHERE uid = ?";
	$stmt = $conn->prepare($imgreq);
	$stmt->bind_param('s', $_SESSION['rmid']);
	if($stmt->execute()){$result = $stmt->get_result();
						 
						 $data = $result->fetch_assoc();
						 
						 //Var holding email address of user
						 $email_address = $data['email_address'];
						}
	$stmt->close();



//Setting var to confirm action for pet removal
//$confirm = strip_tags($_POST['confirm']);

//Function to confirm removal set b
if(isset($_POST['confirm'])){
	
	
	//Retrieve pet data (pet id)  before deletion of user data / used to remove images of pet
	$pet_data = "SELECT * FROM pets WHERE uid = ?";
	$stmt = $conn->prepare($pet_data);
	$stmt->bind_param('s', $_SESSION['rmid']);
	if($stmt->execute()){$result = $stmt->get_result();
						while($array = $result->fetch_assoc()){
							
							//Var holding pet id
							$pid = $array['pid'];
							
							//Var holding image filename of pet
							$photo = $array['image'];
							
						
						 //Deleting photo of pet from images directory
						 //Doing nothing if pets image is default image / prevents default image from being erased
						 if($photo == "default_pic.png"){}else{unlink("../images/images/$photo");}
							 
							 //Deleting pets QR code from QR code directory
							 unlink("../images/qr/$pid-qr.png");
									
						//Unsetting uid of interacting user
						unset($_SESSION['rmid']);
		
							
							
						
							
						}
						 
						 
						}
	$stmt->close();
	//Removing all pet data
	
	
	
	//Deleting row associated with pet
	
	$removeuser = "DELETE FROM users WHERE uid = ?";
	$stmt = $conn->prepare($removeuser);
	$stmt->bind_param('s', $_SESSION['rmid']);
	if($stmt->execute()){
		
}else{echo "there was an error deleting user";}
	$stmt->close();
	
	$removepets = "DELETE FROM pets WHERE uid = ?";
	$stmt = $conn->prepare($removepets);
	$stmt->bind_param('s', $_SESSION['rmid']);
	if($stmt->execute()){
						
						 //Setting var contaiing status of removal
						 $_SESSION['removestatus'] = "<font color='green'>User has been removed</font>";
						 
						 
		
	}else{echo "there was an error deleting pet";}
		$stmt->close();
		unset($_SESSION['rmid']);
	
				//Redirecting user back to 'my pets' details page
				header("location: user_mgmt.php");

						 exit;
	
						}else{
	
}


//Site settings config id
$configid = "1";

//Selecting website title from site settings
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();

?>



<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - User Management / Remove User</title>
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
      <td align="center"><h3>Manage Users / Remove Users</h3>
		
		
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
	 <td>This will remove all data and pet data associated with the user you are interacting with <strong><i><?php echo $email_address; ?></i></strong></td>
	  </tr>
    <tr>
      <td align="center">
		  
		  <!------------Start of confirmation form ------------>
		
		  <table width="450" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td align="center">
		
		  <form action="remove_user.php" method="post">
		<table width="450" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>      
	    <td colspan="6">&nbsp;</td>
	    </tr>
    <tr>
      <td colspan="6" align="center"><h2>Continue?</h2></td>
      </tr>
    <tr>
      <td width="29"><input type="hidden" name="complete" value="<?php echo $_SESSION['rmid']; ?>"></td>
      <td width="124" align="center"><input type="hidden" name="uid" value="<?php echo $_SESSION['rmid']; ?>"></td>
      <td width="134" align="center"><input type="submit" name="confirm" value="Yes"></td>
		<td width="135" align="center"><a href="user_mgmt.php"><input type="button" value="Cancel"></a></td>
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
		
		</td>
    </tr>
  </tbody>
</table>

		  
		<!------------End of confirmation form ------------>
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