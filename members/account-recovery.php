<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();

//Setting session vars to authorize password reset
$_SESSION['recreq'] = strip_tags($_REQUEST['reqid']);


//Selecting user via seession vars
$sql = "SELECT id FROM users WHERE recreq = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $_SESSION['recreq']);
if($stmt->execute()){$result = $stmt->get_result();
					 
					 //Var holding result of row count
					$match_result = $result->num_rows;
					
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
<title><?php echo $website_title; ?> - Forgotten Password</title>
<meta name="description" content="<?php echo $website_title; ?> - Reset your password and account support.">
	
	
<style type="text/css">
body,td,th {
	font-family: "Arial";
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
	
	
	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		
		<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4">
		<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="193">&nbsp;</td>
      <td width="306">&nbsp;</td>
      <td width="536" align="center"><h2><?php echo $website_title; ?></h2></td>
      <td width="65">&nbsp;</td>
      <td width="400" align="right">&nbsp;</td>
      </tr>
  </tbody>
</table>

		
		</td>
      </tr>
    <tr>
      <td width="10%">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="10%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">
		
		
		
		
		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="75">&nbsp;</td>
      <td colspan="2" align="center">
		
		  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td align="center" bgcolor="#FFFFC9">
		  
		  <h2>Account Recovery</h2>
		  
		  <strong>Welcome to the password recovery center.</strong><br />
		  Use this feature to reset your account password if you have forgotten it.</td>
    </tr>
  </tbody>
</table>
		
		<p></p>
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="98">&nbsp;</td>
      <td colspan="2" align="center">
		
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="33%" valign="top">&nbsp;</td>
      <td align="center" width="33%">
		<p></p>
		  		  <form action="reset_confirm.php" method="post">

		  <table width="401" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">
		
		<?php										   
															   
if(is_null($_SESSION['recreq'])){


echo "session is invalid";


}elseif($match_result == 1){echo '




						<p></p>								   															   

Click proceed to continue the password reset process.<br />
<table width="100%" border="0">
<tr>
<td align="center"><input type="submit" value="Proceed"></td>
</tr>
    </table>
';}else{echo "Invalid request";}
		  ?>
		
		</td>
    </tr>
  </tbody>
</table>
		  
		  </form>  
			</td>
      <td width="33%">&nbsp;</td>
    </tr>
  </tbody>
</table>

		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="173">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">&nbsp;
	
		
		
		</td>
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