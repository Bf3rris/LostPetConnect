<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();

//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}


//Retrieving communication method to match communication method - 0 = call 1 = text message
if(isset($_REQUEST['id'])){$id = strip_tags($_REQUEST['id']); $count = 0;}else{if(isset($_REQUEST['mid'])){$id = strip_tags($_REQUEST['mid']); $count = 1;}}

	
	if($count == 0){
	//Using call log id and pet owners id to select call log details from db
	$view = "SELECT * FROM call_log WHERE id = ? AND uid = ?";
	$stmt = $conn->prepare($view);
	$stmt->bind_param('is', $id, $_SESSION['uid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 //Locators phone number
						 $phone_number = $array['from_number'];
						 //Var holding notes data
						$note = $array['notes'];
						}
	$stmt->close();
	
	}else{
		
		//Using mesage log id and pet owners uid to select locators phone number from db
		$view = "SELECT from_number, notes FROM message_log WHERE id = ? AND uid = ?";
	$stmt = $conn->prepare($view);
	$stmt->bind_param('is', $id, $_SESSION['uid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 
						 //Locators phone number
						 $phone_number = $array['from_number'];
						 
						 //Variable holding notes data
						$note = $array['notes'];
						}
	$stmt->close();
		
		
	}
	


?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?>  - Notes</title>
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
      <td width="712" align="center"><h2><?php echo $website_title; ?> </h2></td>
      <td width="62">&nbsp;</td>
    </tr>
    <tr>
      <td height="50">&nbsp;</td>
      <td align="center">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="6%">&nbsp;</td>
      <td width="6%">&nbsp;</td>
      <td width="6%">&nbsp;</td>
      <td width="69%">&nbsp;</td>
      <td width="13%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">
		  
		  
		  
		  <?php
		 
if(isset($_SESSION['update'])){echo "<font color='green'><strong>Notes have been updated</strong></font>"; unset($_SESSION['update']);}

		  ?>
		  
		</td>
      <td>&nbsp;</td>
    </tr>
    </tbody>
</table>

		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">
	<form action="update_notes.php" method="post">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td colspan="5"><h2>Notes about <?php if($count == 0){echo "call from ";}else{echo "message from ";} echo $phone_number;?></h2></td>
    </tr>
    <tr>
      <td colspan="5"><hr width="100%" color="lightgray"></td>
    </tr>
    <tr>
      <td height="28" colspan="5">Write notes about this point of contact.</td>
    </tr>
    <tr>
      <td colspan="5">
		

		  <textarea name="notes" cols="65" rows="10" maxlength="200"><?php if(isset($note)){echo $note;} ?></textarea>  
		
		
		
		</td>
    </tr>
    <tr>
		<td colspan="5"><small><font color="gray">200 character limit</font></small></td>
    </tr>
    <tr>
      <td colspan="5"><input type="hidden" name="vid" value="<?php echo $id; ?>"><input type="hidden" name="method" value="<?php echo $count; ?>"></td>
    </tr>
    <tr>
      <td width="71">&nbsp;</td>
      <td width="129"><a href="notifications.php"><input type="button" value="Exit - Without Saving"></a></td>
      <td width="115">&nbsp;</td>
      <td width="128"><input type="submit" name="update" value="Update"></td>
      <td width="57">&nbsp;</td>
    </tr>
  </tbody>
</table>

			</form>
		
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