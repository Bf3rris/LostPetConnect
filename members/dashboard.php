<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();


//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


//Retrieve owners personal details
$retrieve = "SELECT firstname FROM users WHERE uid = ?";
$stmt = $conn->prepare($retrieve);
$stmt->bind_param('s', $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
					 $firstname = $data['firstname'];
					}
$stmt->close();

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


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?>  - My Dashboard</title>
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
      <td align="center"><h2><?php echo $website_title; ?> </h2></td>
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
      <td><h2>Welcome,
		  <?php
		  
		  //Greeting owner by firstname
		  echo $firstname;
		  
		  ?>
		  </h2></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
		
		
		<?php  
		
		  
		 
		  //Retrieving # of pets the user has
		  $petcount = "SELECT pid FROM pets WHERE uid = ?";
		  $stmt = $conn->prepare($petcount);
		  $stmt->bind_param('s', $_SESSION['uid']);
		  if($stmt->execute()){$result = $stmt->get_result();
							   $count = $result->num_rows;
							  }
		  $stmt->close();		//Displaying singular or plural noun depending on how many pets are found
							   if($count == 1){$noun = "pet";}elseif($count > 1){$noun = "pets";}else{$noun = "pet";}
							   if($count < 0){
								   
								   //Message if user has no pets in database
								   echo "You have no $noun in the database";}else{echo "You have $count $noun in the database";}
		  
		  ?>
		  <p>
		Use the navigation menu on the left to view your available options.
		
		
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