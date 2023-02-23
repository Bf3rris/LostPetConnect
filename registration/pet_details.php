<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();



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



//Setting var so form doesn't get erased on form refreshes

if(isset($_POST['formref']) === false){}else{
if(strip_tags(strip_tags($_POST['formref'] == "reg"))){
	
//Ssetting session variables  from field inputs / sanitizing data
$_SESSION['firstname'] = strip_tags($_POST['firstname']);
$_SESSION['lastname'] = strip_tags($_POST['lastname']);
$_SESSION['email_address'] = strtolower(strip_tags($_POST['emailaddress']));
$_SESSION['city'] = strip_tags($_POST['city']);
$_SESSION['state'] = strip_tags($_POST['state']);
	if($_SESSION['state'] == "blank"){$_SESSION['stateselect'] = "Select your state"; header("location: index.php"); exit;}
	
$_SESSION['zip'] = strip_tags($_POST['zip']);
$_SESSION['mobilenumber'] = strip_tags($_POST['mobilenumber']);

		
//Checking for user input of 'state'
if($_SESSION['state'] == "Select your state:"){$_SESSION['stateerror'] = "Select a state"; header("location: index.php"); exit;}	
	

//Checking if password fields contains at least 6 chars
if(strlen(strip_tags($_POST['password'])) < 6){$_SESSION['passwordlength'] = "Password is less than 6 characters"; header("location: index.php"); exit;}
if(strlen(strip_tags($_POST['confirmpassword'])) < 6){$_SESSION['passwordlength'] = "Password is less than 6 characters"; header("location: index.php"); exit;}
	

//Setting & password vars / encrypting password
$password = strip_tags(sha1($_POST['password']));
$confirm_password = strip_tags(sha1($_POST['confirmpassword']));

	//Comparing input from both password fields
if($password != $confirm_password){$_SESSION['passwordmatch'] = "Password fields don't match"; header("location: index.php"); exit;}

	//Checking for empty password fields
if(empty($password)){$_SESSION['passwordoneerror'] = "Password is empty"; header('location: index.php'); exit;}
if(empty($confirm_password)){$_SESSION['passwordtwoerror'] = "Confirm Password is empty"; header('location: index.php'); exit;}	

	
//Directing back to registration page if password fields don't match
if($password != $confirm_password){$_SESSION['passworderror'] = "<font color='#ff0000'>Passwords don't match</font>"; header('location: index.php'); exit;}else{$_SESSION['password'] = $password;}

//Preserves password for form if user navigates away then back to admin setup page 
$_SESSION['sp'] = strip_tags($_POST['password']);
	
//Setting var/flag  to count +1 if these chars are located.
$value = 0;
	
//Checking if email address field contains "." and "@". - 2 = valid and will advance to next step
if(strstr($_SESSION['email_address'], "@")){$value = $value+1;}
if(strstr($_SESSION['email_address'], ".")){$value = $value+1;}
	
	
//Value var should equal 2 for valid password
if($value == 2){
	
//Query database to check if phone number is associated with a registered user
$string = "SELECT phone_number FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($string);
$stmt->bind_param('s', $_SESSION['mobilenumber']);

if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Var containing result / row count
					$phonecount = $result->num_rows;
					 
					}
	$stmt->close();
	
	//If phone number exists in datbase
if($phonecount > 0){
	
	//Session var containing number in use error
	$_SESSION['numberinuse'] = "<small>This phone number is associated with another account.</small>";
			  
	//Going back to previous page if page if phone numbber exists
	header("location: index.php");
	
}
	
	
//Query database to check if email address is a registered user
$string = "SELECT email_address FROM users WHERE email_address = ?";
$stmt = $conn->prepare($string);
$stmt->bind_param('s', $_SESSION['email_address']);

if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Var containing result of email address ccheck
					$count = $result->num_rows;
					 
					}
	$stmt->close();
	

	
	
	
//If email is associated with another user
if($count > 0){
	
	
	//unsetting email session variable
	unset($_SESSION['email_address']);
	
	//Creating session var for existing email address error / direct page to previous page
	$_SESSION['emailexistserror'] = "<small>Email address already exists in database</small>"; header("location: index.php");
 exit;
}}else{
	
	//Setting session var for invalid email address error
	$_SESSION['invalidemail'] = "Email address isn't valid"; header("location: index.php"); exit;}


}

//Checking for empty fields / directing to registration page to complete empty fields
if(empty($_SESSION['firstname'])){$_SESSION['firstnameerror'] = "<font color='#ff0000'>First name is blank</font>"; header("location: index.php"); exit;}
if(empty($_SESSION['lastname'])){$_SESSION['lastnameerror'] = "<font color='#ff0000'>Last name is blank</font></font>"; header("location: index.php"); exit;}
if(empty($_SESSION['email_address'])){$_SESSION['emailerror'] = "<font color='#ff0000'>email address is blank</font>"; header("location: index.php"); exit;}
if(empty($_SESSION['mobilenumber'])){$_SESSION['mobilenumbererror'] = "<font color='#ff0000'>Phone number is empty</font>"; header("location: index.php"); exit;}
if(empty($_SESSION['city'])){$_SESSION['cityerror'] = "<font color='#ff0000'>City is blank</font></font>"; header("location: index.php"); exit;}
if(empty($_SESSION['state'])){$_SESSION['stateerror'] = "<font color='#ff0000'>State is blank</font>"; header("location: index.php"); exit;}
if(empty($_SESSION['zip'])){$_SESSION['ziperror'] = "<font color='#ff0000'>Zip code is empty</font>"; header("location: index.php"); exit;}

}
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Registration / Pet Details</title>
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
	
	
	
	
	<table width="500" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="134">&nbsp;</td>
      <td width="405">&nbsp;</td>
      <td width="95">&nbsp;</td>
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
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
      <td align="center"><strong><u>Pet details</u> </strong>Step 2 of 3</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Input details about your pet.<br />You can add more pets after initial registration is complete.</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td rowspan="11" valign="top">
		
		  <!------Start of pet details form---->
		  <form action="upload_photo.php" method="post">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2" valign="top">
		
		  <?php 
		  
		  //Displaying error message if pet name field is blank
		  if(isset($_SESSION['petnameerror']))
		  {echo $_SESSION['petnameerror'];
		   //Unsetting error message
		   unset($_SESSION['petnameerror']);
											 }
		  ?>
	
		
		</td>
    </tr>
    <tr>
      <td width="9%">Name</td>
      <td width="78%">
        
        <input type="text" name="petname" value="<?php if(isset($_SESSION['petname'])){echo $_SESSION['petname'];} ?>"></td>
      </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
		  
		  
		  <?php 
		  
		  //Displaying error message if pet gender field is empty
		  if(isset($_SESSION['petgendererror']))
		  {echo $_SESSION['petgendererror'];
		   //Unsetting error message
		   unset($_SESSION['petgendererror']);
		   }
		  ?>
		
		</td>
    </tr>
    <tr>
      <td>Gender</td>
      <td><select name="gender">
		  <option value="male">Male</option>
		  <option value="female">Female</option>
		  </select>
		
		</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php
		  
		  //Displaying error message if pet age field ie empty
		  if(isset($_SESSION['petageerror'])){
	  echo $_SESSION['petageerror'];
			  //Unsetting error message
unset($_SESSION['petageerror']);}
 ?>
		
		</td>
    </tr>
    <tr>
      <td>Age</td>
      <td><input type="number" name="petage"  max="99" min="1" value="<?php
		  //Diplaying pet age if already saved as session var
		  if(isset($_SESSION['petage'])){echo $_SESSION['petage'];}?>"></td>
      </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2">About my pet
		  
		  <?php
		  
		  //Displaying example description if user has inputted one
		  if(isset($_SESSION['description']) == false){
			  echo "
			  <textarea name='description' id='description' cols='55' rows='15' onCLick='clear();'>Describe your pet. Include details such identifiable marks, behavior, etc.</textarea>";
			  
		  }else{
			  //Displaying user inputted description if session var is set
			  if(isset($_SESSION['description'])){
			  echo "
        <textarea name='description' id='description' cols='55' rows='15'>$_SESSION[description]</textarea>";
			}else{echo "<textarea name='description' id='description' cols='55' rows='15'>Enter a description of your pet.</textarea>";}
		  }
	  ?>
			</td>
      </tr>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><input type="hidden" name="detailkey" value="detail"></td>
      </tr>
    <tr>
      <td colspan="2" align="center">
        
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="82" align="center"><a href="index.php"><input type="button" value="&larr; Back"></a></td>
              <td width="77">&nbsp;</td>
              <td width="22">&nbsp;</td>
              <td width="22">&nbsp;</td>
              <td width="97" align="center"><input type="submit" value="Next"></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5" align="center"><a href="exit_reg.php"><input type="button" value="Exit without Saving"></a></td>
              </tr>
            </tbody>
        </table>      </td>
      </tr>
    </tbody>
</table>
		  </form>
			  <!------Pet details form ends  here---->
	  
		 
		
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
      </tr>
    <tr>
      <td height="18">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
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

	
	
</body>
</html>