<?php

//Starting mysql connection
require('../connection.php');

//Starting user session
session_start();

	//Checking session for logged in status
	if(isset($_SESSION['id'])){}else{header("location: index.php");}


//setting session uid
if(isset($_REQUEST['uid'])){$_SESSION['uid'] = strip_tags($_REQUEST['uid']);}


//Sets var from hidden value from personal data form to allow of update only when form is submitted
if(isset($_POST['data'])){$key = strip_tags($_POST['data']);}else{$key = "null";}

//Flag allowing submission to occur if field posts
if($key == "formdata"){

	//Posting fields from personal details form / sanitizing fields
	$firstnameupdate = strip_tags($_POST['firstname']);
	$lastnameupdate = strip_tags($_POST['lastname']);
	$emailaddressupdate = strip_tags($_POST['email_address']);
	
	//Retrieving phone number and email address for matching & preventing conflicts
	$current = "SELECT email_address, phone_number FROM users WHERE uid = ?";
	$stmt = $conn->prepare($current);
	$stmt->bind_param('s', $_SESSION['uid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$data = $result->fetch_assoc();
						 $em = $data['email_address'];
						 $pm = $data['phone_number'];
						}
	$stmt->close();
	
	//Checking if email address is associated with another account
    $check = "SELECT email_address FROM users WHERE email_address = ?";
	$stmt = $conn->prepare($check);
	$stmt->bind_param('s', $emailaddressupdate);
	if($stmt->execute()){$result = $stmt->get_result();
						 
						 //Var containing numerical result of email address match
						$count = $result->num_rows;
						 
						
						 
						 //Checking if email address in array is same as on account
						if($emailaddressupdate == $em){$count = 0;}
						}
						$stmt->close(); 
	
	
	
	//Function for results of possible matching email address in database of users
	if($count != 0){
	
		if($em == $emailaddressupdate){echo "email same as on account.";}else{
			
			//Setting session var with error message if email address is already in database / Redirecting back to settings
		$_SESSION['emailerror'] = "<font color='green'><strong>Email address is associated with another account.</strong></font>"; header("location: user_view.php"); exit;
		}
	}
	//Setting email address var to be used in update 
	$phonenumberupdate  = strip_tags($_POST['phone_number']);
	
	
	//Query database to check if phone number exists with another user
$string = "SELECT phone_number FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($string);
$stmt->bind_param('s', $phonenumberupdate);

if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Var containing result of phone number to rows
					$phonecount = $result->num_rows;
					 
					 //Setting array as var
					 $pd = $result->fetch_assoc();
					 
					 //Setting var 
					 $phonematch = $pd['phone_number'];
					 
					 //Comparing result of query for phone number to phone number on interacting users account
					 //Setting $phonecount var to zero to prevent conflict & allowing update of data
					 if($phonematch == $pm){$phonecount = 0;
					}
					}
	$stmt->close();
	
	//If phone number does exist in database, update is allowed.
if($phonecount > 0){
	if($pm == $phonematch){}else{
		
		//Setting session var for error message to alert user of existing phone number / redirecting back to settings
	$_SESSION['numberinuse'] = "<font color='green'><strong>Phone number is associated with another user.</strong></font>"; header("location: user_view.php"); exit;}

}
	//Posting / sanitizing input fields from personal details
	$cityupdate = strip_tags($_POST['city']);
	$stateupdate = strip_tags($_POST['state']);
	$zipupdate = strip_tags($_POST['zip']); 
	
	//Checking for empty fields / setting empty session var if empty/
      if(empty($firstnameupdate) || empty($lastnameupdate) && empty($emailaddressupdate) && empty($phonenumberupdate) && empty($cityupdate) && empty($stateupdate) && empty($zipupdate)){$_SESSION['error'] = "<font color='#ff0000'><strong>All fields are required.</strong></font>."; header("location: user_view.php");
	  exit; }else{
	
	
	
    //Updating personal details received from user
	$updatestring ="UPDATE users SET firstname = ?, lastname = ?, email_address = ?, phone_number = ?, city = ?, state = ?, zip = ? WHERE uid = ?";
	$stmt = $conn->prepare($updatestring);
	$stmt->bind_param('ssssssss', $firstnameupdate, $lastnameupdate, $emailaddressupdate, $phonenumberupdate, $cityupdate, $stateupdate, $zipupdate, $_SESSION['uid']);
	if($stmt->execute()){
		
		//Setting temp session var on successful update
		$_SESSION['update'] = "<font color='green'><strong>Settings have been updated.</strong></font>";
	
		//Setting temp var to use for redirecting to user view
		$tempvar = $_SESSION['uid'];
		
		//Unsetting current users uid from session
	unset($_SESSION['uid']);
		
		//Redirecting to user view with refreshed data
	header("location: user_view.php?uid=$tempvar");
		exit;
	}else{
			//Setting temp var to use for redirecting to user view
		$tempvar = $_SESSION['uid'];
		
		//Unsetting current users uid from session
	unset($_SESSION['uid']);
			
			//Setting temp session var on failed update
			$_SESSION['update'] = "<font color='#ff0000'><strong>Something didn't work right.</strong></font>";
	}
	$stmt->close();
	
	
		//Loads settings page with updated data
	header("location: user_view.php?uid=$tempvar");
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
						 
						}
	
	$stmt->close();
	
}

//Site settings id
$configid = "1";

//Retrieving website title
$settings_sql = "SELECT website_title, domain FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $website_title = $array['website_title'];
					 $domain = $array['domain'];
					 
					}
$stmt->close();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Settings</title>
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
      <td align="center"><h3>User Management - Viewing User</h3></td>
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
      <td width="122"></td>
      <td width="453" align="center">
		  
		  
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

		  
		  
		  //Displaying status of message for completion of renerating new qr code / new pet id
		  if(isset($_SESSION['completestatus'])){echo $_SESSION['completestatus']; unset($_SESSION['completestatus']);}
		  ?>
		
		</td>
      <td width="75">&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="134" colspan="3">
		
		
		  
		  <table width="650" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">
		  
		  
		  
		  <!---------Start of personal details form--------->
		  <form action="user_view.php" method="post">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
		<td colspan="3"><strong><u>Personal Details</u></strong></td>
      <td width="8">&nbsp;</td>
      <td width="77">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="174">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="74">First name</td>
      <td width="6">&nbsp;</td>
      <td width="151"><input type="text" name="firstname" size="24" maxlength="24" value="<?php echo $firstname; ?>"></td>
      <td>&nbsp;</td>
      <td>Last name</td>
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
      <td>Email address</td>
      <td>&nbsp;</td>
      <td><input type="text" name="email_address" size="24" maxlength="24" value="<?php echo $email_address; ?>"></td>
      <td>&nbsp;</td>
      <td>Phone number</td>
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
		<td colspan="3"><strong><u>Location</u></strong></td>
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
      <td>City</td>
      <td>&nbsp;</td>
      <td><input type="text" name="city" size="24" maxlength="32" value="<?php echo $city; ?>"></td>
      <td>&nbsp;</td>
      <td>State</td>
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
      <td>Zip</td>
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
		  
		<table width="650" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		  
		
		<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="74">&nbsp;</td>
      <td width="149"><strong><u>Password Settings</u></strong></td>
      <td width="158">&nbsp;</td>
      <td width="230">&nbsp;</td>
      <td width="39">&nbsp;</td>
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
      <td>Password</td>
      <td colspan="2">Force Password Reset</td>
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
		 
		</td>
    </tr>
  </tbody>
</table>

		  
		  
		  <table width="650" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="123">&nbsp;</td>
      <td width="193">&nbsp;</td>
      <td width="165">&nbsp;</td>
      <td width="82">&nbsp;</td>
      <td width="87" align="center"><a href="remove_user.php?uid=<?php echo $_SESSION['uid']; ?>">Remove user</a></td>
    </tr>
  </tbody>
</table>

		  
		  <p></p>
		  
 <table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
	  
	   <tr>
	  <td><strong>Users pets</strong></td>
	  </tr>
    <tr>
      <td> <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  
	  <?php
	  
	  //Selecting all pets belonged to the uid
	  $petlist = "SELECT * FROM pets WHERE uid = ?";
	  $stmt = $conn->prepare($petlist);
	  $stmt->bind_param('s', $_SESSION['uid']);
	  if($stmt->execute()){$result = $stmt->get_result();
						  
						   //Row count of pets
						  $count = $result->num_rows;
						   
						   // If or if no pets associated with uid
						   if($count == 0){echo "User has no pets to display.";}else{
						  while($pets = $result->fetch_assoc()){
							  
							  if($pets['name'] == ""){$pets['name'] = "Incomplete draft";}
							  
			echo"
    <tr>
      <td>$pets[name]</td><td><a href='$domain/view/petid=$pets[pid]' target='_blank'>View</a> / <a href='qr.php?petid=$pets[pid]' target='_blank'>QR Code</a></td><td><a href='generate_id.php?petid=$pets[pid]'>Generate new QR code</a></td>
    </tr>";
						  }}}
	  ?>
	  
  </tbody>
</table></td>
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