<?php

//Startting session for form input / navigation
session_start();



//Creating session vars with website settings / sanitizing input
if(isset($_POST['formref'])){
	if(strip_tags($_POST['formref']) == "ws"){
$_SESSION['domain'] = strip_tags($_POST['domain']);
$_SESSION['website_title'] = strip_tags($_POST['website_title']);
$_SESSION['contact'] = strip_tags($_POST['contact']);
		$_SESSION['composer_path'] = strip_tags($_POST['composer_path']);
	$_SESSION['xfn'] = strip_tags($_POST['xfn']);
		$_SESSION['space_url'] = strip_tags($_POST['space_url']);
	$_SESSION['pid'] = strip_tags($_POST['pid']);
		$_SESSION['token'] = strip_tags($_POST['token']);
	

}
//Checking for empty fields 
if(empty($_SESSION['domain']) || empty($_SESSION['website_title']) || empty($_SESSION['contact']) || empty($_SESSION['composer_path']) || empty($_SESSION['pid']) || empty($_SESSION['token']) ||empty($_POST['space_url'])){$_SESSION['emptyinput'] = "<font color='#ff0000'><strong>All fields must be completed.</strong></font>";

//redirecting back to proper page if empty fields exist
header("location: website_setup.php"); exit;}
}else{}
?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title>Lost Pet Connect - Installation Wizard [MySQL Setup]</title>
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
      <td colspan="2" align="center"><strong><u>MySQL settings</u></strong> Step 3 of 3</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">Input credentials for your MySQL user that will be connecting to the database.<strong> Required privileges</strong>: SELECT, WRITE, INSERT, DELETE, UPDATE,</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">&nbsp;</td>
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

		<form action="setup_overview.php" method="post">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="36%">Database location</td>
      <td width="2%">&nbsp;</td>
      <td width="54%"><input name="mysql_location" type="text" value="<?php
		  
		  //Displaying first name if exists as session variable
		  if(isset($_SESSION['mysql_location'])){echo $_SESSION['mysql_location'];}?>" size="24" maxlength="24"></td>
      <td width="7%">&nbsp;</td>
      <td width="1%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Commonly: '<em>localhost</em>'</td>
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
      <td>Database name</td>
      <td>&nbsp;</td>
      <td><input name="database_name" type="text" size="24" maxlength="32" value="<?php
		  
		  //Displaying city if set as session variable
		  if(isset($_SESSION['database_name'])){echo $_SESSION['database_name'];}?>"></td>
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
      <td><?php
		  
		  //Displaying error message if last name field is blank
		  if(isset($_SESSION['lastnameerror'])){echo $_SESSION['lastnameerror'];
													 //Unsetting error message
												unset($_SESSION['lastnameerror']);
											   }
		  	?>																								
	      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>MySQL username</td>
      <td>&nbsp;</td>
      <td><input name="mysql_username" type="text" value="<?php
		  
		  //Displaying  username if set as session variable
		  if(isset($_SESSION['mysql_username'])){echo $_SESSION['mysql_username'];}?>" size="24" maxlength="24"></td>
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
      <td colspan="4">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>MySQL password</td>
      <td>&nbsp;</td>
      <td><input type="password" name="mysql_password" value="<?php
		  
		  //Displaying email address if set as session variable
		  if(isset($_SESSION['mysql_password'])){echo $_SESSION['mysql_password'];}?>" size="24" maxlength="32"></td>
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="hidden" name="formref" value="ms"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">
		
		<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
		<td width="54%"><a href="website_setup.php"><input type="button" value="&larr; Previous"></a></td>
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