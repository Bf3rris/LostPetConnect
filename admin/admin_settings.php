<?php

//Start mysqli connection
require('../connection.php');

//Starting admin session
session_start();

//Checking session for logged in status
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Sets var from hidden value from personal data form to allow of update only when form is submitted

//Flag allowing submission to occur
if(isset($_POST['formref'])){

	//Posting fields from personal details form / sanitizing fields
	$firstnameupdate = strip_tags($_POST['firstname']);
	$lastnameupdate = strip_tags($_POST['lastname']);
	$emailaddressupdate = strip_tags($_POST['email_address']);
	
	//Checking if email address is associated with another account
    $check = "SELECT email_address FROM admin WHERE email_address = ?";
	$stmt = $conn->prepare($check);
	$stmt->bind_param('s', $emailaddressupdate);
	if($stmt->execute()){$result = $stmt->get_result();
						 
						 //Var containing numerical result of email address check
						$count = $result->num_rows;
						}
						$stmt->close(); 
	
	//Checking if email address is same as on target users account
	$matchcheck = "SELECT email_address FROM admin WHERE id = ?";
	$stmt = $conn->prepare($matchcheck);
	$stmt->bind_param('s', $_SESSION['id']);
	if($stmt->execute()){$result = $stmt->get_result();
						 
//Fetching row for data
 $emailx = $result->fetch_assoc();
						 //Setting var with email address of current user
						 $emailmatch['email_address'] = $emailx['email_address'];
						 
}
 $stmt->close();
	
	//Function for results of possible matching email address in database of users
	if($count > 0){
	
		if($emailmatch['email_address'] == $emailaddressupdate){}else{
			
			//Setting session var with error message if email address is already in database / Redirecting back to settings
		$_SESSION['emailerror'] = "Email address is associated with another administrator."; header("location: admin_settings.php"); exit;
		}
	}

	
	//Checking for empty fields / setting empty session var if empty/
      if(empty($firstnameupdate) || empty($lastnameupdate) && empty($emailaddressupdate) && empty($phonenumberupdate) && empty($cityupdate) && empty($stateupdate) && empty($zipupdate)){$_SESSION['error'] = "<font color='#ff0000'>All fields are required</font>.";
	  
	  //To display page with refreshed data
	  header("location: admin_settings.php");
	  exit;
	  }else{
	
	

	
	//Updating personal details for admin user
	$updatestring ="UPDATE admin SET first_name = ?, last_name = ?, email_address = ? WHERE id = ?";
	$stmt = $conn->prepare($updatestring);
	$stmt->bind_param('sssi', $firstnameupdate, $lastnameupdate, $emailaddressupdate, $_SESSION['id']);
	if($stmt->execute()){
		
		//Setting temp session var on successful update
		$_SESSION['update'] = "<font color='green'>Settings have been updated</font>";}else{
			
			
			//Setting temp session var on failed update
			$_SESSION['update'] = "<font color='#ff0000'>Something didn't work right.</font>";
	}
	$stmt->close();
	
	
		//Refreshing settings page with updated data
	header("location: admin_settings.php");
		exit;
	}
}else{
	

	//Selecting users personal data from database
	$request = "SELECT * FROM admin WHERE id = ?";
	$stmt = $conn->prepare($request);
	$stmt->bind_param('s', $_SESSION['id']);
	if($stmt->execute()){$result = $stmt->get_result();
						$data = $result->fetch_assoc();
						 
						//Setting personal details to be displayed in form
						 $firstname = $data['first_name'];
						 $lastname = $data['last_name'];
						 $email_address = $data['email_address'];
						
						}
	$stmt->close();
}

//Site settings id
$configid = "1";

//Retrieving website title
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
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
<title><?php echo $website_title; ?> - Admin Settings</title>
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
      <td>
		
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
      <td height="79">&nbsp;</td>
      <td align="center"><h3>&nbsp;</h3></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">
		  
		  
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td colspan="2"><h2>Administrator Settings</h2></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php
		  //Displaying personal details update success message / unsetting message
		  if(isset($_SESSION['update'])){echo $_SESSION['update']; unset($_SESSION['update']);}
		  
		  
		  //Displaying error message if personal details update failed / unsetting error message
		  if(isset($_SESSION['error'])){echo $_SESSION['error']; unset($_SESSION['error']);}
		  
		  //Displaying email address error if exists / unsetting message
		 if(isset($_SESSION['emailerror'])){
			 echo $_SESSION['emailerror'];
		 unset($_SESSION['emailerror']);}
		  
		  //Displaying possible password error messages if set / unsetting error messages
		  if(isset($_SESSION['passwordstatus'])){echo $_SESSION['passwordstatus']; unset($_SESSION['passwordstatus']);}
		  if(isset($_SESSION['passworderror'])){echo $_SESSION['passworderror']; unset($_SESSION['passworderror']);}
		  
		  

		  ?>
        
        
        </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><hr width="100%" color="lightgray"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">View and edit your administrator account settings.</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
		
		  
		   
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    </tbody>
</table>

		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    
    <tr>
      <td height="134" colspan="3">
		
		
		  
		  <table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">
		  
		  
		  
		  <!---------Start of personal details form--------->
		  <form action="admin_settings.php" method="post">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
		<td></td>
		<td colspan="3"><strong><u>Personal Details</u></strong></td>
		<td width="8">&nbsp;</td>
      <td width="77">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="174">&nbsp;</td>
      <td width="174">&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
		<td colspan="3"><small>First name</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
		<td><small>Last name</small></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="74">&nbsp;</td>
      <td colspan="3"><input type="text" name="firstname" size="24" maxlength="24" value="<?php echo $firstname; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="text" name="lastname" value="<?php echo $lastname; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="74">&nbsp;</td>
      <td width="6">&nbsp;</td>
      <td width="151">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
		<td colspan="3"><small>Email address</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3"><input type="text" name="email_address" size="24" maxlength="24" value="<?php echo $email_address; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
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
      <td><input type="hidden" name="formref" value="personal"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center"><input type="submit" value="Update Information"></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>
		  </form>	
		<!---------End of personal details form--------->
		</td>
    </tr>
  </tbody>
</table>

				
		
		
		
		</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
  </tbody>
</table>
		  
		<table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		  
		  <!---------Start of password update form--------->
		<form action="change_pass.php" method="post">
		<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="34">&nbsp;</td>
      <td colspan="2"><strong><u>Password Settings</u></strong></td>
      <td width="150">&nbsp;</td>
      <td width="27">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="136">&nbsp;</td>
      <td width="153">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
		<td><small>New Password</small></td>
      <td><input type="password" name="passone"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
		<td><small>Confirm password</small></td>
      <td><input type="password" name="passtwo"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="hidden" name="formref" value="password"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Update Password"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
		  </form>
		<!---------End of password form--------->
		</td>
    </tr>
  </tbody>
</table>

  
		  
		  
		  
</td>
      <td height="61">&nbsp;</td>
    </tr>
    <tr>
      <td height="400">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

		
		</td>
    </tr>
  </tbody>
</table>

	
	
	
</body>
</html>