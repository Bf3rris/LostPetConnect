<?php

//Starting MySQL connecrtion
require('../connection.php');


//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT website_title FROM site_settings WHERE id = ?";
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
		
		
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
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
		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="98">&nbsp;</td>
      <td colspan="2" align="center">
		
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="16%" valign="top">&nbsp;</td>
      <td align="center" width="76%">
		<p></p>
		  <table width="401" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		
		  <form action="password-request.php" method="post">
		<table width="400" border="0">
  <tr>
    <td colspan="3">Enter the email address associated with your <?php echo $website_title; ?> account.</td>
    </tr>
  <tr>
    <td colspan="3" align="center"></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><input type="text" name="email" value="email@domain.com" id="email" onClick="email.value=''"></td>
    <td width="144" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="116" height="20">&nbsp;</td>
    <td width="122" align="center"><input type="submit" value="Next step" /></td>
    <td height="20">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"></td>
    <td align="center"></td>
  </tr>
    </table>
		  </form>
		
		</td>
    </tr>
  </tbody>
</table>
<center><a href="../members">Cancel, return to login</a></center>
		  
		  
			</td>
      <td width="8%">&nbsp;</td>
    </tr>
  </tbody>
</table>

		
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="98">&nbsp;</td>
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