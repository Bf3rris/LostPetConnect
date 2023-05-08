<?php

//Start mysqli connection
require('../connection.php');

//Starting user session
session_start();


//Directing to login if not logged in
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Retrieve admin users basic personal details
$retrieve = "SELECT first_name FROM admin WHERE id =?";
$stmt = $conn->prepare($retrieve);
$stmt->bind_param('s', $_SESSION['id']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
					 $firstname = $data['first_name'];
					}
$stmt->close();


//Site settings config id
$configid = "1";

//Query for site settings
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
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Dashboard</title>
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
      <td height="50">&nbsp;</td>
      <td align="center"><h3>Admin Dashboard</h3></td>
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
      <td width="250"><h2>Welcome,
		  <?php
		  
		  //Greeting owner by firstname
		  echo $firstname;
		  
		  ?>
		  </h2></td>
      <td width="118">&nbsp;</td>
      <td width="132" align="center"><?php echo date('m.d.y'); ?></td>
    </tr>
    <tr>
      <td colspan="3"><hr width="100%" color="lightgray"></td>
      </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
		
Use the navigation menu on the left to access the available administrative functions.
		 
		
		
		
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