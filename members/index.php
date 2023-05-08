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
$stmt->bind_param('i', $configid);
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
<title><?php echo $website_title; ?> - Owner Login</title>
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
      <td height="202">
		
		<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="150" height="50">&nbsp;</td>
      <td width="400" align="center"><h2><?php echo $website_title; ?> </h2>
		<h3>Owner Login </h3></td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td rowspan="6" align="center" valign="top">
		<?php 
		  
		  //Displaying login error
		 if(isset($_SESSION['loginerror'])){
		  echo $_SESSION['loginerror'];
		  unset($_SESSION['loginerror']);
			 }
		  
		  //Displaying password reset message
		  if(isset($_SESSION['passwordstatus'])){
		  echo $_SESSION['passwordstatus'];
		  unset($_SESSION['passwordstatus']);
		  }
		  
		  ?>
		  
		<form action="authenticate.php" method="post">
		<table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">Email address</td>
    </tr>
    <tr>
      <td align="center"><input type="text" name="email_address"></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">Password</td>
    </tr>
    <tr>
      <td align="center"><input type="password" name="password"></td>
	  </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><input type="submit" value="Login"></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>

		
		
		
		
		</td>
    </tr>
  </tbody>
</table>
		  </form>
		
		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="20">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="78">&nbsp;</td>
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
  </tbody>
</table>
		
		</td>
    </tr>
  </tbody>
</table>
<center>
	
	<a href="forgot_password.php">Forgot password?</a>
	</center>

	
	
	
</body>
</html>