<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Posting / sanitizing  email address input
$email = strip_tags($_POST['email']);
	
//Querying database to see if email address exists in database
$query = "SELECT email_address FROM users WHERE email_address = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Array holding results
					 $array = $result->fetch_assoc();
					 
					 //Setting with row result
					 $exists = $result->num_rows;
					 
					 //If email address exists setting var to send email
					 if($exists == 1){$email_address = $array['email_address'];}
					 }
$stmt->close();



//Site settings config ID
$configid = "1";

//Query for site settingse
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Var holding results
					$array = $result->fetch_assoc();
					 
					 $website_title = $array['website_title'];
					 
					 //Used to build recovery link for email
					 $domain = $array['domain'];
					 
					 //Email address of support / used as sender of recovery email
					 $support_email = $array['support_email'];
					 
					}

$stmt->close();


//Setting var to use as key in reset process 
$recreq = str_shuffle(sha1(rand(11111111, 99999999)));


//Link that is emailed to user to complete password recovery
$reclink = $domain."/members/account-recovery.php?reqid=" . $recreq;



?>



<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
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
      <td width="536" align="center"><h2><?php echo $website_title; ?></h2></td>
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
      <td height="162">&nbsp;</td>
      <td colspan="2" align="center">
		
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="33%" valign="top">&nbsp;</td>
      <td align="center" width="33%">
		<p></p>
		  <table width="401" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">
		
		<?php				
		  
		  
	if (empty($email)){echo "Email can't be empty<br /><input type='button' value='Previous page' onclick='history.back()'>";}else{												
															 if ($exists == 1){

			//Updating user row with reset request code
			$recreq_sql = "UPDATE users SET recreq = ? WHERE email_address = ?";
			$stmt = $conn->prepare($recreq_sql);
			$stmt->bind_param('ss', $recreq, $email);
			$stmt->execute();
			$stmt->close();
																 
																 
																 

echo "An email containing a password recovery link has been sent to your email address<br /><strong><i>$email</i></strong><p></p> Please check your Spam or Junk folder for this email.";

																 
//Email sent to user upon reset request
$message = "
A password recovery request has been submitted for this account.
To continue with the process, click the following link:
$reclink


Sincerely,
The Support Team

If you did not initiate this password request, please contact support immediately via Email $support_email


";
//Mail call						
mail($email_address, '[$website_title] Your Password Request', $message, '', "-f$support_email");

}else{echo 
																 "
																 The email address entered <br /><strong><i>$email</i></strong><br />was not found.<br /><br /> Please check your email address and<br /> try again. <br />";
	 	  echo "<p></p> <input type='button' onclick='history.back()' value='Try again'>
		  
		  
		  ";
	 }
	}
								?>	  
		
		</td>
    </tr>
  </tbody>
</table>
<center><?php 
	
	if($exists == "1"){echo "
	<a href='../members'>Go to Login</a>";}
		
		?>
		</center>
		  
		  
			</td>
      <td width="33%">&nbsp;</td>
    </tr>
  </tbody>
</table>

		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="98">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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