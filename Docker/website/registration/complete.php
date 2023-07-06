<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();


//Site settings config ID
$configid = "1";

//Query for site settings 
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 //Var holding webite tile
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();




//Flag allowing completion of registration
$_SESSION['complete'] = "cl";

//Initial password reset column (recreq) data
$recreq = sha1(rand(000000, 999999));

if($_SESSION['complete'] = "cl"){$action = "cl";}elseif($_POST['action'] = "cl"){$action = "cl";}else{}
//
if($action == "cl"){
	
	//Generating users UID
	$uid = str_shuffle(substr(sha1($_SESSION['email_address']), 0, 15));

	//Generating pet id
	if(isset($_SESSION['petid'])){$pid = $_SESSION['petid'];}else{
	$pid = substr(str_shuffle(md5(date('his'))), 0, 8);
	}
	
	//Setting flag variable for registration completion / final value must = 2 to complete
	$value = 0;
	
	//Setting registration date
	$reg_date = date('m.d.y');

	//Checking if exmil exists in database / prevents duplicate entries if user goes back after completion
	$row_count = "SELECT id FROM users WHERE email_address = ?";
	$stmt = $conn->prepare($row_count);
	$stmt->bind_param('s', $_SESSION['email_address']);
	if($stmt->execute())$result = $stmt->get_result();
	$num_rows = $result->num_rows;
}
$stmt->close();



if(is_null($_SESSION['email_address'])){header("location: index.php");}else{

//Inserting pet owners detaisl into table
$insertowner = "INSERT INTO users (uid, firstname, lastname, email_address, phone_number, password, city, state, zip, reg_date, recreq) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertowner);
$stmt->bind_param('sssssssssss', $uid, $_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['email_address'], $_SESSION['mobilenumber'], $_SESSION['password'], $_SESSION['city'], $_SESSION['state'], $_SESSION['zip'], $reg_date, $recreq);
if($stmt->execute()){$value = $value+1;
					
					 //Checking for user uploaded photo / using default image if user doesn't upload photo
					 if(isset($_SESSION['img'])){
					$default_image = $_SESSION['img'];
					 }else{$default_image = "default_pic.png";}
					 
					
					 
					 //Default status of 1, status of 2 means pet is missing
					 $status = "1";
					 
					 //Intentionally empty for registration
					 $status_date = "";
//Inserting pet detaisl into table					 
$insertpet = "INSERT INTO pets (pid, uid, name, age, timenoun, description, image, gender, status, status_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtt = $conn->prepare($insertpet);
$stmtt->bind_param('ssssssssss', $pid, $uid, $_SESSION['petname'], $_SESSION['petage'], $_SESSION['timenoun'], $_SESSION['description'], $default_image, $_SESSION['petgender'], $status, $status_date);
if($stmtt->execute()){$value = $value+1;}else{echo "There has been an error, return to registration <a href='registration.php'>Click Here</a>";}
$stmtt->close();
					 
							
					
					}else{
	//Error message if insertion fails
	echo "There has been an error, return to registration <a href='registration.php'>Click Here</a>";}
$stmt->close();

	//QR code generates if a value of 2 exists / completing registration.
if($value == 2){
	
	
	//QR code filename
	$filename = $pid."-qr.png";
	
	//Generating QR code using Google Devs/Charts
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."/view/view.php?petid=".$pid);
	
	//Storing QR code
	file_put_contents("../images/qr/".$filename, $data);
}{
	
}
	
	}

	


?>


<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Upload Photo</title>
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
	
	
	<table width="500" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="134">&nbsp;</td>
      <td width="405">&nbsp;</td>
      <td width="95">&nbsp;</td>
      </tr>
    <tr>
      <td align="center"></td>
      <td align="center"><h2><?php echo $website_title; ?> </h2></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><h3>Registration Complete</h3></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">Welcome to Lost Pet Connect</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td rowspan="9" align="center">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center"><img src="../images/success.png"></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="7%" align="center"><a href="../members">Sign in</a> to your account</td>
      </tr>
    </tbody>
</table>
			
		
		</td>
    </tr>
  </tbody>
</table>

		
		
		</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td height="48">&nbsp;</td>
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
  </tbody>
</table>

		
		
	  </td>
    </tr>
  </tbody>
</table>

	
	
</body>
</html>