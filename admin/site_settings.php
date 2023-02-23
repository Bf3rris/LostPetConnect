<?php

//Starting mysqli connection
require('../connection.php');

//Starting user session
session_start();

//Checking if user is logged in / redirecting to login if not
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Grabbing site ssettings
$configid = "1";

$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $xfn = $array['xfn'];
					 $website_title = $array['website_title'];
					 $domain = $array['domain'];
					 $support_email = $array['support_email'];
					 $photo_dir = $array['photo_dir'];
					 $qr_dir = $array['qr_dir'];
					 }

//Key var prevents unintentional form submits 
if(isset($_POST['formref'])){
	
//Posting / sanitizing site settings 
	$fxfn = strip_tags($_POST['xfn']);
	$fwt = strip_tags($_POST['website_title']);
$fdm = rtrim(strip_tags($_POST['domain']), "/");
$fsc = strip_tags($_POST['support_email']);
$fpd = strip_tags($_POST['photo_directory']);
$fqd = strip_tags($_POST['qr_directory']);
	
	//Updating website settings row
	$update_sql = "UPDATE site_settings SET website_title = ?, domain = ?, support_email = ?, photo_dir = ?, qr_dir = ?, xfn = ? WHERE id = ?";
	$stmt = $conn->prepare($update_sql);
	$stmt->bind_param('ssssssi', $fwt, $fdm, $fsc, $fpd, $fqd, $fxfn, $configid);
	if($stmt->execute()){
		//Setting var for success message
		$_SESSION['updatestatus'] = "<font color='green'><strong>Settings have been updated.</strong></font>";
		
						//Redirecting to site settings page upon completion
						header("location: site_settings.php");
						 exit;
						
						}else{
		//Setting error message if update fails
		$_SESSION['updatestatus'] = "<font color='#ff0000'><strong>There has been an error.</strong></font>";}
	
	//Redirecting to site settings
	header("location: site_settings.php");
	exit;	
}


?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Admin / Website Settings</title>
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
		
		<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="133" height="53">&nbsp;</td>
      <td width="22">&nbsp;</td>
      <td width="120">&nbsp;</td>
      <td width="425" align="center"><h2>Lost Pet Connect</h2></td>
      <td width="114">&nbsp;</td>
      <td width="34">&nbsp;</td>
      <td width="52">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="5"><?php require('navigation.php'); ?></td>
      <td>&nbsp;</td>
      <td colspan="3" rowspan="8" align="center">
		
		  
		  
		  
		  <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><h2>Website Settings</h2></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
		
		  <?php
		  //Displaying site settings update/failure message if exists
		  if(isset($_SESSION['updatestatus'])){echo $_SESSION['updatestatus'];
											  //Unsetting update/failure message
											  unset($_SESSION['updatestatus']);}
		  ?>
		
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><hr width="100%" color="lightgray"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>View and manage details about your website.</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

		 <table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><form action="site_settings.php" method="post">
        <table width="500" border="0" cellspacing="0" cellpadding="0">
		  <tbody>
		    </tbody>
          <tbody>
            <tr>
              <td width="143"></td>
              <td width="117">&nbsp;</td>
              <td width="120">&nbsp;</td>
              <td width="87">&nbsp;</td>
              <td width="33">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
				<td><u><strong>Proxy Phone Number</strong></u></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td><input type="tel" name="xfn" value="<?php echo $xfn; ?>" size="11" maxlength="11"></td>
				<td><small>(Ex. 12120004567)</small></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
				<td colspan="2"><small>Number must be obtained from your SignalWire account.</small></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><strong><u>Website Settings</u></strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Website title</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><input type="text" name="website_title" value="<?php echo $website_title; ?>"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Domain or IP</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><input type="text" name="domain" value="<?php echo $domain; ?>"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3"><small>QR codes are generated using this domain / IP.</small></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">Support Contact</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><input type="text" name="support_email" value="<?php echo $support_email; ?>"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2"><strong><u>Image Locations</u></strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Photo directory</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><input type="text" name="photo_directory" value="<?php echo $photo_dir; ?>"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">QR Code directory</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><input type="text" name="qr_directory" value="<?php echo $qr_dir; ?>"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><small>*Note: If image directories are changed, you must manually move image files to new directory.</small></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><input type="hidden" name="formref" value="ss"></td>
              <td><input type="submit" value="Update Settings"></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
          <tbody>
          </tbody>
		  </table>
		</form>
		
		</td>
    </tr>
  </tbody>
</table>


		
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="194">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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