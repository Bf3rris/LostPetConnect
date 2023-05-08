<?php

//Start mysqli connection
require('../connection.php');

//Starting user session
session_start();

//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


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
<title><?php echo $website_title; ?> - My Pets</title>
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
      <td align="center"><h2><?php echo $website_title; ?></h2></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="50">&nbsp;</td>
      <td align="center">
		
		<?php
		  
		  //Displaying status if pet was removed / set from 'remove pet' selection
		  if(isset($_SESSION['removestatus'])){echo $_SESSION['removestatus'];
												  //Unsetting pet removed message
												  unset($_SESSION['removestatus']);
												  } 
		  //Displayed if pet has been added to database
		    if(isset($_SESSION['createstatus'])){echo $_SESSION['createstatus'];
												  //Unsetting successful pet addition message
												  unset($_SESSION['createstatus']);
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
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="113"><h2>My Pets</h2></td>
      <td width="291">&nbsp;</td>
      <td width="96" align="center"><a href="add_pet.php">+ Add Pet</a></td>
    </tr>
    <tr>
      <td colspan="3"><hr width="100%" color="lightgray"></td>
    </tr>
	  <tr>
	  <td colspan="3">Select a pet to view or manage details.</td>
	  </tr>
    <tr>
      <td colspan="3" align="left">
		
		 <!---------------iframe containg list of pets---------------->
		<iframe src="pet_list.php" width="350" height="500" frameborder="0"></iframe>
		  
		
		
		
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