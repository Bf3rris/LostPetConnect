<?php

//Startting session for form input / navigation
session_start();

?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title>Lost Pet Connect - Installation Wizard [Admin Setup]</title>
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
      <td colspan="2" align="center"><strong><u>Administrative account</u></strong> Step 1 of 3</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">Complete the fields below to create your first administrative account. An administrative account has full access and complete control over everything in the database.</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="152">&nbsp;</td>
      <td width="268">&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php if(isset($_SESSION['emptyinput'])){echo $_SESSION['emptyinput']; unset($_SESSION['emptyinput']);} ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="11" align="center" valign="top">
		  
		  
		  		  <!------Personal Details form starts  here---->

		<form action="website_setup.php" method="post">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>First name</td>
      <td width="2%">&nbsp;</td>
      <td width="38%"><input name="firstname" type="text" value="<?php
		  
		  //Displaying first name if exists as session variable
		  if(isset($_SESSION['firstname'])){echo $_SESSION['firstname'];} ?>" size="24" maxlength="24"></td>
      <td>&nbsp;</td>
      <td width="1%">&nbsp;</td>
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
    <tr>
      <td>Last name</td>
      <td>&nbsp;</td>
      <td><input name="lastname" type="text" value="<?php
		  
		  //Displaying last name if set as session variable
		  if(isset($_SESSION['lastname'])){echo $_SESSION['lastname'];}?>" size="24" maxlength="24"></td>
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
      <td>
		  
		  <?php
		   //Displaying error message if email address is invalid
		  		   if(isset($_SESSION['emailinvalid'])){echo $_SESSION['emailinvalid'];
														//Unsetting error message for unmatched passwords
														unset($_SESSION['emailinvalid']);}
		  
		  ?>
		  </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Email address</td>
      <td>&nbsp;</td>
      <td><input name="emailaddress" type="text" value="<?php
		  
		  //Displaying email address if set as session variable
		  if(isset($_SESSION['email_address'])){echo $_SESSION['email_address'];}?>" size="24" maxlength="32"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2"><small>You'll use this email address to login to your Admin account.</small></td>
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
      <td><?php
		  //Displaying error message if password isn't long enough
		  if(isset($_SESSION['passwordlength'])){echo $_SESSION['passwordlength'];
												//Unsetting password length error message
												unset($_SESSION['passwordlength']);}
		  //Displaying error message if password field is empty
		  if(isset($_SESSION['passwordoneerror'])){echo $_SESSION['passwordoneerror'];
												  //Unsetting empty password error message
												  unset($_SESSION['passwordoneerror']);}
		  //Displaying error message if confirmation password field is empty
		   if(isset($_SESSION['passwordtwo'])){echo $_SESSION['passwordtwo'];
											  //Unsetting empty confirmation password error message
											  unset($_SESSION['passwordtwo']);}
		  
		  			//Displaying error message if password and confirmation password fields don't match
		  		   if(isset($_SESSION['passwordmatch'])){echo $_SESSION['passwordmatch'];
														//Unsetting error message for unmatched passwords
														unset($_SESSION['passwordmatch']);}
		 

		  ?>
		
		
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Password</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="password" name="password" size="24" maxlength="24" value="<?php
		  
		  //Displaying password if set as session variable
		  if(isset($_SESSION['sp'])){echo $_SESSION['sp'];}?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Confirm password</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="password" name="confirmpassword" size="24" maxlength="24" value="<?php if(isset($_SESSION['sp'])){echo $_SESSION['sp'];}?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="hidden" name="formref" value="as"></td>
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
		<td width="54%"><a href="exit_reg.php"><input type="button" value="Exit without Saving"></a></td>
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