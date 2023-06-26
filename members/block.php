<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Directing to login if not logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}

//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();




//Retrieving communication method to match communication method - 0 = call 1 = text message
if(isset($_REQUEST['id'])){$id = strip_tags($_REQUEST['id']); $count = 0;}else{if(isset($_REQUEST['mid'])){$id = strip_tags($_REQUEST['mid']); $count = 1;}}


if($count == 0){
	
	//Using call log id and pet owners uid to select locators phone number from call log
$search = "SELECT from_number FROM call_log WHERE id = ? AND uid = ?";
$stmt = $conn->prepare($search);
$stmt->bind_param('is', $id, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
				   $array = $result->fetch_assoc();
					 
					 //Variable holding number to block
				$_SESSION['block_number'] = $array['from_number'];
				   }
$stmt->close();
}else{
		//Using message log id and pet owners uid to select locators phone number from call log
		$search = "SELECT from_number FROM message_log WHERE id = ? AND uid = ?";
		$stmt = $conn->prepare($search);
		$stmt->bind_param('is', $id, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
				   $array = $result->fetch_assoc();
					 
					 	//Variable holding number to block
					 	$_SESSION['block_number'] = $array['from_number'];
				   }
	
$stmt->close();
	
}
	
	
	


?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?>  - Restrict number</title>
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
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="6%">&nbsp;</td>
      <td width="6%">&nbsp;</td>
      <td width="6%">&nbsp;</td>
      <td width="69%">&nbsp;</td>
      <td width="13%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><h2>&nbsp;</h2></td>
      <td align="center">
        
        
        
        <?php
		 
if(isset($_SESSION['update'])){echo "something here"; unset($_SESSION['update']);} 

		  ?>
        
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
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
		  
		  <?php
		  
		  $idk = "SELECT restricted FROM users WHERE uid = ?";
		  $stmt = $conn->prepare($idk);
		  $stmt->bind_param('s', $_SESSION['uid']);
		  if($stmt->execute()){$result = $stmt->get_result();
							   $array = $result->fetch_assoc();
							   $match = $array['restricted'];
							  	}
		  $stmt->close();
		  
		  if(strstr($match, $_SESSION['block_number'])){echo "Phone number is already blocked.<br />Return to <a href='notifications.php'>notifications</a>";}else{
		  echo"
		  <!------beginning of form------>
	<form action='update_list.php' method='post'>
		<table width='500' border='0' cellspacing='0' cellpadding='0'>
  <tbody>
    <tr>
      <td colspan='5'><h2>Restrict Number</h2></td>
    </tr>
    <tr>
      <td colspan='5'><hr width='100%' color='lightgray'></td>
    </tr>
    <tr>
      <td colspan='5'>This action will block the phone number, <strong>$_SESSION[block_number]</strong> from being able to contact you.<br /><center>This action <strong>can</strong> be undone in <a href='settings.php'>settings</a></center></td>
    </tr>
    <tr>
      <td colspan='5'>&nbsp;</td>
    </tr>
    <tr>
		<td colspan='5'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='5'><input type='hidden' name='bn' value='<?php echo $_SESSION[block_number]; ?>'></td>
    </tr>
    <tr>
      <td width='71'>&nbsp;</td>
      <td width='129'><a href='notifications.php'><input type='button' value='Cancel'></a></td>
      <td width='115'>&nbsp;</td>
      <td width='128'><input type='submit' name='block' value='Block'></td>
      <td width='57'>&nbsp;</td>
    </tr>
  </tbody>
</table>

			</form>";
		
		  }
	
	?>
		  <!----end of form--->
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