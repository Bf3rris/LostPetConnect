<?php
//Start MySQLi connection
require('../connection.php');

//Starting admin session
session_start();


//Directing to login if not logged in
if(isset($_SESSION['id'])){}else{header("location: index.php");}

//Site settings id
$configid = "1";

//Retrieving website title
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
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
<title><?php echo $website_title; ?> - User Management</title>
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
      <td align="center">&nbsp;</td>
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
      <td width="325"><h2>User Management</h2></td>
      <td width="175">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><hr width="100%" color="lightgray"></td>
    </tr>
    <tr>
      <td colspan="2">Manage your users. Select a user to view or modify an account.</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </tbody>
</table>

		<table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		
		
	  <?php 
	  
	  $list = "SELECT uid, firstname, lastname FROM users";
	  $stmt = $conn->prepare($list);
	  if($stmt->execute()){$result = $stmt->get_result();
						   $user_count = $result->num_rows;
						   
						   if($user_count == 0){echo "
						   
						   <center>
						   There are no users to display
						   </center>
						   ";}else{
						 while($data = $result->fetch_assoc()){
						  
						  
	  echo "
	  <table width='500' border='0' cellspacing='1' cellpadding='1'>
  <tbody>
	<tr>
      <td width='64'>&nbsp;</td>
      <td width='436'>Name</td>
      <td width='200'>View</td>
    </tr>
	  <tr>
      <td>&bull;</td>
      <td>$data[lastname], $data[firstname]</td>
      <td><a href='user_view.php?uid=$data[uid]'>View User</td>
    </tr>";}}
						  }
		  ?>
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