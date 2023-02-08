<?php

//Start mysqli connection
require('../connection.php');

//Starting user session
session_start();

//Directing to login if not logged in
if($_SESSION['uid'] == ""){header("location: index.php");}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lost Pet Connect - My Pets</title>
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
      <td align="center">
		
		<?php
		  
		  //Displaying status if pet was removed / set from 'remove pet' selection
		  if($_SESSION['removestatus'] != ""){echo $_SESSION['removestatus'];
												  //Unsetting pet removed message
												  unset($_SESSION['removestatus']);
												  } 
		  //Displayed if pet has been added to database
		    if($_SESSION['createstatus'] != ""){echo $_SESSION['createstatus'];
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
      <td colspan="3" align="center">
		
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
      <td align="center">LPC;</td>
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