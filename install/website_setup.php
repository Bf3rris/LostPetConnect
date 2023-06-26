<?php

//Startting session for form input / navigation
session_start();

//Setting var so form doesn't get erased on form refreshes
if(is_null($_POST['formref'])){}elseif(isset($_POST['formref'])){
	if(strip_tags($_POST['formref'] == "as")){
//Setting session variables  from field inputs / sanitizing data
$_SESSION['firstname'] = strip_tags($_POST['firstname']);
$_SESSION['lastname'] = strip_tags($_POST['lastname']);
$_SESSION['email_address'] = strtolower(strip_tags($_POST['emailaddress']));

//Checking for empty input fields
	if(empty($_SESSION['firstname']) || empty($_SESSION['lastname'])){$_SESSION['emptyinput'] = "All fields must be completed."; header("location: admin_setup.php"); exit;}
	
//Checking if password fields contains at least 6 chars
if(strlen(strip_tags($_POST['password'])) < 6){$_SESSION['passwordlength'] = "Password must be at least 6 characters"; header("location: admin_setup.php"); exit;}
if(strlen(strip_tags($_POST['confirmpassword'])) < 6){$_SESSION['passwordlength'] = "Password must be at least 6 characters"; header("location: admin_setup.php"); exit;}
	

//Setting & password vars / encrypting password
$password = strip_tags(sha1($_POST['password']));
$confirm_password = strip_tags(sha1($_POST['confirmpassword']));
$confirm_password = strip_tags(sha1($_POST['confirmpassword']));

	

	//Checking for empty password fields
if(empty($password)){$_SESSION['passwordoneerror'] = "Password is empty"; header('location: admin_setup.php'); exit;}
if(empty($confirm_password)){$_SESSION['passwordtwoerror'] = "Confirm Password is empty"; header('location: admin_setup.php'); exit;}	

	
//Preserves password for form if user navigates away then back to admin setup page 
$_SESSION['sp'] = strip_tags($_POST['password']);
	
//Directing back to registration page if password fields don't match
if($password != $confirm_password){$_SESSION['passworderror'] = "<font color='#ff0000'><strong>Passwords don't match</strong></font>"; header('location: admin_setup.php'); exit;}else{$_SESSION['password'] = $password;}

//Setting admin password once all validations are complete
$_SESSION['password'] = $password;

	
//Setting var/flag  to count +1 if these chars are located.
$value = 0;
	
//Checking if email address field contains "." and "@". - 2 = valid and will advance to next step
if(strstr($_SESSION['email_address'], "@")){$value = $value+1;}
if(strstr($_SESSION['email_address'], ".")){$value = $value+1;}
	
	
//Value var should equal 2 for valid password
if($value != 2){$_SESSION['emailinvalid'] = "Email address is invalid."; header("location: admin_setup.php"); exit;}
}
}
?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title>Lost Pet Connect - Installation Wizard [Website Setup]</title>
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
	
	<table width="700" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="34">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="46">&nbsp;</td>
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td colspan="2" align="center"><h2>Lost Pet Connect</h2></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><h3>Installation Wizard</h3></td>
		<td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><strong><u>Website settings</u></strong> Step 2 of 3</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">Complete the following fields to set up your website. All fields are required.</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="152">&nbsp;</td>
      <td width="268"><?php if(isset($_SESSION['emptyinput'])){echo $_SESSION['emptyinput']; unset($_SESSION['emptyinput']);} ?></td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="11" align="center" valign="top">
		  
		  
		  		  <!------Personal Details form starts  here---->

		<form action="mysql_setup.php" method="post">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="26%">Domain URL</td>
      <td width="6%">&nbsp;</td>
      <td width="57%"><input type="text" name="domain" value="<?php if(isset($_SESSION['domain'])){echo $_SESSION['domain'];} ?>" size="24" maxlength="32"></td>
      <td width="10%">&nbsp;</td>
      <td width="1%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><small>(Example: https://domain.com</small></td>
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
      <td>Website Title</td>
      <td>&nbsp;</td>
      <td><input name="website_title" type="text" value="<?php if(isset($_SESSION['website_title'])){echo $_SESSION['website_title'];} ?>" size="24" maxlength="24"></td>
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
      <td>Support contact</td>
      <td>&nbsp;</td>
      <td><input type="text" name="contact" value="<?php if(isset($_SESSION['contact'])){echo $_SESSION['contact'];} ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><small>This is the email address your members will use as a point of contact for support.</small></td>
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
      <td colspan="2"><strong>SignalWire Settings</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="28">Phone number:</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="tel" name="xfn" size="12" maxlength="12" value="<?php if(isset($_SESSION['xfn'])){echo $_SESSION['xfn'];}?>">
        <small>(Example: +12221234567)</small></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Space URL</td>
      <td>&nbsp;</td>
      <td><input name="space_url" type="text" size="40" maxlength="64"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(Example: username.signalwire.com)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="28">Project ID</td>
      <td>&nbsp;</td>
      <td><input name="pid" type="text" size="40" maxlength="64"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Token</td>
      <td>&nbsp;</td>
      <td><input name="token" type="text" size="40" maxlength="64"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
		<td>(Note: Token starts with "<strong>PT</strong>")</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td></td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">Path to Composer autoloader</td>
      <td><input type="text" name="composer_path" value=""></td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td><small>(Example: '../vendor/autoload.php')<br />
        </small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input type="hidden" name="formref" value="ws"></td>
      <td>&nbsp;</td>
      <td><small><strong>Note</strong>: Dependant files are located in the 'comm' directory.</small></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">&nbsp;</td>
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
      <td colspan="4">
		
		<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
		<td width="54%"><a href="admin_setup.php"><input type="button" value="&larr; Previous"></a></td>
      <td width="9%">&nbsp;</td>
      <td width="2%">&nbsp;</td>
      <td width="18%"><input type="submit" id="submit"value="Next"></td>
    </tr>
  </tbody>
</table>
			
		
		</td>
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
				  <!------Personal Details form ends  here---->

		
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
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