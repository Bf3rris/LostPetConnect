<?php

//Start mysqli connection
require('../connection.php');

//Start user session
session_start();

//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


if(isset($_POST['data'])){$key = strip_tags($_POST['data']);}else{$key = "null";}
//Sets var from hidden value from personal data form to allow of update only when form is submitted


//Flag allowing submission to occur
if($key == "formdata"){

	//Posting fields from personal details form / sanitizing fields
	$firstnameupdate = strip_tags($_POST['firstname']);
	$lastnameupdate = strip_tags($_POST['lastname']);
	$emailaddressupdate = strtolower(strip_tags($_POST['email_address']));
	
	//Checking if email address is associated with another account
    $check = "SELECT email_address FROM users WHERE email_address = ?";
	$stmt = $conn->prepare($check);
	$stmt->bind_param('s', $emailaddressupdate);
	if($stmt->execute()){$result = $stmt->get_result();
						 
						 //Var containing numerical result
						$count = $result->num_rows;
						
						}
						$stmt->close(); 
	
	//Checking if email is same as on logged in users account
	$matchcheck = "SELECT email_address FROM users WHERE uid = ?";
	$stmt = $conn->prepare($matchcheck);
	$stmt->bind_param('s', $_SESSION['uid']);
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
		$_SESSION['emailerror'] = "<font color='#ff0000'><strong>Email address is associated with another account.</strong></font>"; header("location: settings.php"); exit;
		}
	}
	//Setting email address var to be used in update 
	$phonenumberupdate  = strip_tags($_POST['phone_number']);
	
	
	//Query database to check if phone number exists with another user or is existing number for interacting user
$string = "SELECT uid, phone_number FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($string);
$stmt->bind_param('s', $phonenumberupdate);
	
	//Setting flag var / 0 means phone number is unused / 0 also means phone number is interacting users current phone number to prevent conflict during updates
	$phonecount = 0;
if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Var containing result of matches for phone number
					$phonecount = $result->num_rows;
					 
					 //Array fetched for uid data
					 $pd = $result->fetch_assoc();
					 
					 //If uid of phone number matches interacting userers phone number $phonecount returns to zero
					 if($pd['uid'] == $_SESSION['uid']){$phonecount = 0;}
					 
					 //Comparing phone number
					 $phonematch = $pd['phone_number'];
					}
	$stmt->close();
	
	//If phone number does exist function
if($phonecount > 0){
	if($phonenumberupdate == $phonematch){}else{
		
		//Setting session var for error message to alert user of existing phone number / redirecting back to settings
	$_SESSION['numberinuse'] = "<font color='#ff0000>Phone number is associated with another member.</strong></font>"; header("location: settings.php"); exit;}

}
	//Posting / sanitizing input fields from personal details
	$cityupdate = strip_tags($_POST['city']);
	$stateupdate = strip_tags($_POST['state']);
	$zipupdate = strip_tags($_POST['zip']); 
	
	//Checking for empty fields / setting empty session var if empty/
      if(empty($firstnameupdate) || empty($lastnameupdate) && empty($emailaddressupdate) && empty($phonenumberupdate) && empty($cityupdate) && empty($stateupdate) && empty($zipupdate)){$_SESSION['error'] = "<font color='#ff0000'>All fields are required</font>.";
	  
	  //To display page with refreshed data
	  header("location: settings.php");
	  exit;
	  }else{
	
	
	
	
	
	
	//Updating personal details received from user
	$updatestring ="UPDATE users SET firstname = ?, lastname = ?, email_address = ?, phone_number = ?, city = ?, state = ?, zip = ? WHERE uid = ?";
	$stmt = $conn->prepare($updatestring);
	$stmt->bind_param('ssssssss', $firstnameupdate, $lastnameupdate, $emailaddressupdate, $phonenumberupdate, $cityupdate, $stateupdate, $zipupdate, $_SESSION['uid']);
	if($stmt->execute()){
		
		//Setting temp session var on successful update
		$_SESSION['update'] = "<font color='green'><strong>Settings have been updated</strong></font>";}else{
			
			
			//Setting temp session var on failed update
			$_SESSION['update'] = "<font color='#ff0000'><strong>Something didn't work right.</strong></font>";
	}
	$stmt->close();
	
	
		//Loads settings page with updated data
	header("location: settings.php");
		exit;
	}
}else{
	

	//Selecting users personal data from database
	$request = "SELECT * FROM users WHERE uid = ?";
	$stmt = $conn->prepare($request);
	$stmt->bind_param('s', $_SESSION['uid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$data = $result->fetch_assoc();
						 
						//Setting personal details to be displayed in form
						 $firstname = $data['firstname'];
						 $lastname = $data['lastname'];
						 $email_address = $data['email_address'];
						 $phone_number = $data['phone_number'];
						 $city = $data['city'];
						 $state = $data['state'];
						 $zip = $data['zip'];
						 $restricted_list = $data['restricted'];
						 
						}
	
	
	
}



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
		<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - My Settings</title>
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
      <td colspan="3">
		
		<?php require('top.php'); ?>
		
		</td>
      </tr>
    <tr>
      <td width="126" height="50">&nbsp;</td>
      <td width="712" align="center"><h2><?php echo $website_title; ?></h2></td>
      <td width="62">&nbsp;</td>
    </tr>
    <tr>
      <td height="52">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">
		  
		  
		
		<table width="450" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="153"><h2>Settings</h2></td>
      <td width="422" align="center">
		  
		  
		   <?php
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
		  
		  //Displaying phone number in use error message / unsetting error message
		  		  if(isset($_SESSION['numberinuse'])){echo $_SESSION['numberinuse']; unset($_SESSION['numberinuse']);}

		  if(isset($_SESSION['passwordmatch'])){echo $_SESSION['passwordmatch']; unset($_SESSION['passwordmatch']);}
		  
		  
		  		  if(isset($_SESSION['restriction'])){echo $_SESSION['restriction']; unset($_SESSION['restriction']);}

		  

		  
		  
		  ?>
		
		</td>
      <td width="75">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><hr width="100%" color="lightgray"></td>
      </tr>
    <tr>
      <td colspan="2">View and modify your personal details and security information.</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="134" colspan="3">
		
		
		  
		  <table width="650" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">
		  
		  
		  
		  <!---------Start of personal details form--------->
		  <form action="settings.php" method="post">
		<table width="450" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong><u>Personal Details</u></strong></td>
      <td width="7">&nbsp;</td>
      <td width="73">&nbsp;</td>
      <td width="9">&nbsp;</td>
      <td width="186">&nbsp;</td>
    </tr>
    <tr>
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
      <td><small>First name</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><small>Last name</small></td>
    </tr>
    <tr>
      <td width="27">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="188"><input type="text" name="firstname" size="24" maxlength="24" value="<?php echo $firstname; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="text" name="lastname" value="<?php echo $lastname; ?>"></td>
    </tr>
    <tr>
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
      <td><small>Email address</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><small>Phone number</small></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="text" name="email_address" size="24" maxlength="24" value="<?php echo $email_address; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="text" name="phone_number" size="10" maxlength="10" value="<?php echo $phone_number; ?>"></td>
    </tr>
    <tr>
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
		<td><strong><u>Location</u></strong></td>
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
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><small>City</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><small>State</small></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="text" name="city" size="24" maxlength="32" value="<?php echo $city; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>
		
		  <select name="state">
			  
			  <option value="<?php echo $state; ?>"><?php echo $state; ?></option>
        <option value="blank">Select your state:</option>
        <option value="AL">Alabama</option>
        <option value="AK">Alaska</option>
        <option value="AS">American Samoa</option>
        <option value="AZ">Arizona</option>
        <option value="AR">Arkansas</option>
        <option value="CA">California</option>
        <option value="CO">Colorado</option>
        <option value="CT">Conneticut</option>
        <option value="DE">Delaware</option>
        <option value="DC">District of Columbia</option>
        <option value="FL">Florida</option>
        <option value="GA">Georgia</option>
        <option value="GU">Guam</option>
        <option value="HI">Hawaii</option>
        <option value="ID">Idaho</option>
        <option value="IL">Illinois</option>
        <option value="IN">Indiana</option>
        <option value="IA">Iowa</option>
        <option value="KA">Kansas</option>
        <option value="KY">Kentucky</option>
        <option value="LA">Louisiana</option>
        <option value="ME">Maine</option>
        <option value="MD">Maryland</option>
        <option value="MA">Massachusetts</option>
        <option value="MI">Michigan</option>
        <option value="MN">Minnesota</option>
        <option value="MS">Mississippi</option>
        <option value="MO">Missouri</option>
        <option value="MT">Montana</option>
        <option value="NE">Nebraska</option>
        <option value="NV">Nevada</option>
        <option value="NH">New Hampshire</option>
        <option value="NJ">New Jersey</option>
        <option value="NM">New Mexico</option>
        <option value="NY">New York</option>
        <option value="NC">North Carolina</option>
        <option value="ND">North Dakota</option>
        <option value="MP">Northern Mariana Islands</option>
        <option value="OH">Ohio</option>
        <option value="OK">Oklahoma</option>
        <option value="OR">Oregon</option>
        <option value="PA">Pennsylvania</option>
        <option value="PR">Puerto Rico</option>
        <option value="RI">Rhode Island</option>
        <option value="SC">South Carolina</option>
        <option value="SD">South Dakota</option>
        <option value="TN">Tennessee</option>
        <option value="TX">Texas</option>
        <option value="UT">Utah</option>
        <option value="VT">Vermont</option>
        <option value="VA">Virginia</option>
        <option value="VI">Virgin Islands</option>
        <option value="WA">Washington</option>
        <option value="WV">West Virginia</option>
        <option value="WI">Wisconsin</option>
        <option value="WY">Wyoming</option>
      </select>
		
		
		</td>
    </tr>
    <tr>
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
      <td><small>Zip</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="text" name="zip" size="5" maxlength="5" value="<?php echo $zip; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="hidden" name="data" value="formdata"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center"><input type="submit" value="Update Information"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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
		  
		  
		  
		  <!---------------->
		  
		  <table width="650" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		  
		  <!---------Start of password update form--------->
		<form action="update_list.php" method="post">
		<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="111">&nbsp;</td>
      <td colspan="2"><strong><u>Restricted numbers</u></strong></td>
      <td width="230">&nbsp;</td>
      <td width="39">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">Restricted numbers are unable to contact you via call or text message.</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3" rowspan="3">
		
		<textarea name="restricted_list" cols="72" rows="8"><?php echo $restricted_list; ?></textarea>
		
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
      <td colspan="3"><br />(example: +12223334567, +13334448901 (Include country code, separate by comma))</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="124"><input type="hidden" name="rl" value="rl"></td>
      <td width="146">&nbsp;</td>
      <td><input type="submit" name="submit" value="Update"></td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
		  </form>
		<!---------End of personal details form--------->
		</td>
    </tr>
  </tbody>
</table>
		  
		  <p></p>
		  <!----------------->
		  
		  
		<table width="650" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		
		  <!---------Start of password update form--------->
		<form action="change_pass.php" method="post">
		<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="111">&nbsp;</td>
      <td colspan="2"><strong><u>Password Settings</u></strong></td>
      <td width="230">&nbsp;</td>
      <td width="39">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="124">&nbsp;</td>
      <td width="146">&nbsp;</td>
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
      <td colspan="2" align="left"><input type="hidden" name="formref" value="cp"><font color="lightgray"><small>6 Character minimum</small></font></td>
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
		<!---------End of personal details form--------->
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