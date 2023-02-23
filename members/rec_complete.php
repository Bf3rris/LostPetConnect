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
					 $support_email = $array['support_email'];
					 $domain = $array['domain'];
					}
$stmt->close();



//Query to see if user exists and to use data 
$select = "SELECT uid, email_address FROM users WHERE recreq = ?";
$stmt = $conn->prepare($select);
$stmt->bind_param('s', $_SESSION['recreq']);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $uid = $array['uid'];
					 $count = $result->num_rows;
					 $email_address = $array['email_address'];
					}
$stmt->close();


if($count == 0){echo "no matching rows";}else{
//Checking for empty fields / Setting error var if found / redirecting back to settings on error
if(empty(strip_tags($_POST['passone'])) || empty(strip_tags($_POST['passtwo']))){$_SESSION['passworderror'] = "<font color='#ff0000'><strong>Password is empty</strong></font>"; header("location: reset_confirm.php");}


//Checking if password fields contains at least 6 chars / setting error var if length is long enough/ redirecting to settings page on error
if(strlen(strip_tags($_POST['passone'])) < 6){$_SESSION['passworderror'] = "<font color='#ff0000'><strong>New password must be at least 6 characters.</strong></font>"; header("location: reset_confirm.php"); exit;}
if(strlen(strip_tags($_POST['passtwo'])) < 6){$_SESSION['passworderror'] = "<font color='#ff0000'><strong>New password must be at least 6 characters.</strong></font>"; header("location: reset_confirm.php"); exit;}
	
	//If both password fields are valid the password var will be encrypted and used as new password
if(strip_tags($_POST['passone'] == strip_tags($_POST['passtwo']))){$password = sha1(strip_tags($_POST['passone']));}else{$_SESSION['passwordmatch'] = "<font color='#ff0000'><strong>New passwords don't match</strong></font>"; header("location: reset_confirm.php"); exit;}
	

	//Replacing data in recreq column with filler data
	$fill = str_shuffle(sha1(rand(11111111, 99999999)));
	
	//Updating user row with new password once all password checks have been satisfied
$updatepassword = "UPDATE users SET password = ?, recreq = ? WHERE uid = ?";
$stmt = $conn->prepare($updatepassword);
$stmt->bind_param('sss', $password, $fill, $uid);
if($stmt->execute()){
	
	//Var containing successful update status / redirecting to settings after completion
	$_SESSION['passwordstatus'] = "<font color='green'><strong>Password successfully updated. <br /> Login using your new credentials.</strong></font>"; header("location: index.php"); exit;}else{
	
	//Var containing update error / redirecting to settings 
	$_SESSION['passwordstatus'] = "<font color='#ff0000'><strong>Password unable to be changed.</strong></font>"; header("location: reset_confirm.php"); exit;}
$stmt->close();


}






?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Forgotten Password</title>
<meta name="description" content="<?php echo $website_title; ?> - Reset your password and account support.">
	
	
<style type="text/css">
body,td,th {
	font-family: "Arial";
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
	
	
	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		
		<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4">
		<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="193">&nbsp;</td>
      <td width="306">&nbsp;</td>
		<td width="536" align="center"><h2><?ohp echo $website_title; ?></h2></td>
      <td width="65">&nbsp;</td>
      <td width="400" align="right">&nbsp;</td>
      </tr>
  </tbody>
</table>

		
		</td>
      </tr>
    <tr>
      <td width="10%">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="10%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">
		
		
		
		
		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="75">&nbsp;</td>
      <td colspan="2" align="center">
		
		  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td align="center" bgcolor="#FFFFC9">
		  
		  <h2>Account Recovery</h2>
		  
		  <strong>Welcome to the password recovery center.</strong><br />
		  Use this feature to reset your account password if you have forgotten it.</td>
    </tr>
  </tbody>
</table>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="19">&nbsp;</td>
      <td colspan="2" align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="162">&nbsp;</td>
      <td colspan="2" align="center">
		
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="28%" valign="top">&nbsp;</td>
      <td align="center" width="38%" cellpadding="0">
		<p></p>
		
<table width="301" border="1" bordercolor="lightgray" cellpadding="0" cellspacing="0">
	<tbody>
<tr>
<td align="center">		  
<?php
					
	echo '
<center>
<h2>Invalid request</h2>
</center>';
    ?>
		</td></tr></tbody></table>	
		
		</td>
      <td width="27%">&nbsp;</td>
    </tr>
    <tr>
      <td height="98">&nbsp;</td>
      <td colspan="2" align="center">
		
		  
		
		
		
		
		</td>
      <td width="7%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">&nbsp;
        
        
		  
        
        
        </td>
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