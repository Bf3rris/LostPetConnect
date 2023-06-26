<?php

//Start mysqli connection
require('../connection.php');

//Starting user session
session_start();

//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


//Site settings config ID<h
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();


?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Notifications</title>
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
      <td height="50">&nbsp;</td>
      <td align="center">
		
		
		<?php  
		  
		  //Display number block message if action performed
		    if(isset($_SESSION['block'])){echo "Phone number has been blocked.<br />
			
			<strong><a href='settings.php'><u>View Restricted List</u></a></strong>
			
			";
												  //Unsetting successful pet addition message
												  unset($_SESSION['block']);
		  
		  }
		  
		  
		  ?>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">
		
		<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="146"><h2>Notifications</h2></td>
      <td width="100">&nbsp;</td>
      <td width="254" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><hr width="100%" color="lightgray"></td>
    </tr>
	  <tr>
	  <td colspan="3">View and manage notifications that appear when locators of your pet contact you.</td>
	  </tr>
	  <tr>
		  <td><h3>Calls</h3></td>
	    <td>&nbsp;</td>
	    <td><h3>Text Messages</h3></td>
	    </tr>
    <tr>
      <td colspan="2" align="left" valign="top">
		<iframe src="call_log.php" width="310" frameborder="0"></iframe>
		
		</td>
      <td align="left" valign="top">
		
		  <table width="300" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
        <iframe src="message_log.php" width="310" frameborder="0"></iframe></td>
    </tr>
  </tbody>
</table>
		
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