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
					 
					 //Var holding SignalWire phone number
					 $xfn = $array['xfn'];
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 //Var holding support email contact
					 $support_email = $array['support_email'];
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path'];
					 
					 //Var holding SignalWire project ID
					 $project_id = $array['project_id'];
					 
					 //Var holding SignalWire space URL
					 $space_url = $array['space_url'];
					 
			
					 //Var holding SignalWire auth token
					 $auth_token = $array['token'];
					 }

//Key var prevents unintentional form submits 
if(isset($_POST['formref'])){
	
//Posting / sanitizing site settings 
	
	//Updated SignalWire phone number
	$fxfn = strip_tags($_POST['phone_number']);
	
	//Updated Website title
	$fwt = strip_tags($_POST['website_title']);
	
	//Updated Website url
$fdm = rtrim(strip_tags($_POST['domain']), "/");
	
	//Updated Email address used for client support
$fsc = strtolower(strip_tags($_POST['support_email']));
	
	//Updated Composers autoload path
$cp = strip_tags($_POST['composer_path']);
	
	//Updated SignalWire space URL
	$surl = strtolower(strip_tags($_POST['space_url']));
	
	//Updated SignalWire project ID
	$pi = strip_tags($_POST['project_id']);
	
	//Updated SignalWire auth token
	$at = strip_tags($_POST['auth_token']);

	//Checking for empty variables from form submission with updated website/signalwire settings
	if(empty($fxfn) || empty($fwt) || empty($fdm) || empty($fsc) || empty($cp) || empty($pi) || empty($surl) || empty($at)){$_SESSION['emptyinput'] = "All fields must be completed.";}
	
	
	//Updating website settings row
	$update_sql = "UPDATE site_settings SET website_title = ?, domain = ?, support_email = ?, composer_path = ?, xfn = ?, space_url = ?, project_id = ?, token = ? WHERE id = ?";
	$stmt = $conn->prepare($update_sql);
	$stmt->bind_param('ssssssssi', $fwt, $fdm, $fsc, $cp, $fxfn, $space_url, $pi, $at, $configid);
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
			<meta name="viewport" content="width=device-width, initial-scale=1">
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
		  if(isset($_SESSION['emptyinput'])){echo $_SESSION['emptyinput'];
											  //Unsetting update/failure message
											  unset($_SESSION['emptyinput']);}
				  
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
                      <td width="143">&nbsp;</td>
                      <td colspan="2"><strong><u>Website Settings</u></strong></td>
                      <td width="87">&nbsp;</td>
                      <td width="33">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td width="117">&nbsp;</td>
                      <td width="120">&nbsp;</td>
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
                      <td colspan="3"><small>Example: https://domain.com</small></td>
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
                      <td colspan="2">Composer autoloader path</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      </tr>
                    <tr>
                      <td></td>
                      <td colspan="2"><input type="text" name="composer_path" value="<?php echo $composer_path; ?>"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><small>Dependant files are located in the 'comm' directory.</small></td>
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
                      <td colspan="2"><strong><u>SignalWire Settings</u></strong></td>
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
                      <td colspan="2">Phone number</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><input name="phone_number" type="tel" value="<?php echo $xfn; ?>" maxlength="13"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="3"><small>Example: +12223334567</small></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="3">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">Space URL</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><input name="space_url" type="text" value="<?php echo $space_url; ?>" maxlength="60"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><small>Example: username.signalwire.com</small></td>
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
                      <td colspan="2">Project ID</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><input name="project_id" type="text" value="<?php echo $project_id; ?>" maxlength="60"></td>
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
                      <td colspan="2">Auth Token</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><input name="auth_token" type="text" value="<?php echo $auth_token; ?>" maxlength="60"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><small>Example: PT*******</small></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><input type="hidden" name="formref" value="ss"></td>
                      <td><input type="submit" value="Update Settings"></td>
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