<?php


//Starting MySQL connection
require('../connection.php');

//Starting session
session_start();

//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					 //Var holding composer autoload path
					 $composer_path = $array['composer_path']; 
					 
					 //Var holding SignalWire space url
					 $space_url = $array['space_url'];
					 
					 //Var holding SignalWire phone number
					 $xfn = $array['xfn'];
					 
					 //Var holding SignalWire project id
					$project_id = $array['project_id'];

					 //Var holding SignalWire auth token
					 $token = $array['token'];

					}
$stmt->close();

//Composers autoload
require_once($composer_path);

//Requesting communication method id / setting count flag
if(isset($_REQUEST['id'])){$id = strip_tags($_REQUEST['id']); $count = 0;}else{if(isset($_REQUEST['mid'])){$id = strip_tags($_REQUEST['mid']); $count = 1;}}


//SignalWire stuff
use \SignalWire\LaML\VoiceResponse;
use SignalWire\Rest\Client;


//Retrieve users phone number to call them for connection
$own = "SELECT phone_number FROM users WHERE uid = ?";
$stmt = $conn->prepare($own);
$stmt->bind_param('s', $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Locators phone number
					 $my_number = $array['phone_number'];
					}
$stmt->close();


if($count == 0){
	
//Retrieving locators phone number from call log
$locator = "SELECT from_number FROM call_log WHERE id = ? AND uid = ?";
$stmt = $conn->prepare($locator);
$stmt->bind_param('ss', $id, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding locators phone number
				$lpn = $array['from_number'];
					 
					}
$stmt->close();
}else{
	
	//Retrieving locators phone number from message log to be used in next step
$locator = "SELECT from_number FROM message_log WHERE id = ? AND uid = ?";
$stmt = $conn->prepare($locator);
$stmt->bind_param('ss', $id, $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Locators phone number
				$lpn = $array['from_number'];
	
}
$stmt->close();
}

//Updating column with locators phone number to be dialed 
$updateco = "UPDATE users SET call_out = ? WHERE uid = ?";
$stmt = $conn->prepare($updateco);
$stmt->bind_param('ss', $lpn, $_SESSION['uid']);
$stmt->execute();
$stmt->close();



//Placing call to pet owner
$client = new Client($project_id, $token, array("signalwireSpaceUrl" => "$space_url"));

//Calling user to connect call to locator
 $call = $client->calls
                 ->create("+1".$my_number, // to
						  
						  //SignalWire proxy number
                          $xfn, // from
                          array("url" => "$domain/comm/call_interim.php"

							   )
                 );




?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?></title>
<style type="text/css">
body,td,th {
	font-family: Arial;
}
</style>
</head>

<body>
	
	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="50">&nbsp;</td>
      <td width="600" align="left"><h2>Answer your phone</h2>
		  
		  
		  <strong>1. </strong>First, a call is placed to your phone.<br />
		  <strong>2. </strong>You'll be given a chance to cancel the call before the other party is dialed.
		  
		
	  </td>
      <td width="50">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">Return to <a href="../members/notifications.php">notifications</a></td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

	
</body>
</html>
