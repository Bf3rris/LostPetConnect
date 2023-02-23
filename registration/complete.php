<?php

//Starting MySQL connection
require('../connection.php');

//Starting user session
session_start();


//Site settings config ID
$configid = "1";

//Query for site settings / title
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $domain = $array['domain'];
					 $website_title = $array['website_title'];
					 $photo_dir = $array['photo_dir'];
					 $qr_dir = $array['qr_dir'];
					}
$stmt->close();




//
$_SESSION['complete'] = "cl";

//Initial password reset column/key
$recreq = sha1(rand(0000, 9999));

if($_SESSION['complete'] = "cl"){$action = "cl";}elseif($_POST['action'] = "cl"){$action = "cl";}else{}
//
if($action == "cl"){
	
	//Creates uid for database from email address
	$uid = str_shuffle(substr(sha1($_SESSION['email_address']), 0, 15));

	//Generating pet id (PID))
	if(isset($_SESSION['petid'])){$pid = $_SESSION['petid'];}else{
	$pid = substr(str_shuffle(md5(date('his'))), 0, 8);
	}
	
	//Setting flag variable for registration completion / final must = 2
	$value = 0;
	
	//Setting registration date
	$reg_date = date('m.d.y');

	
		if(is_null($_SESSION['email_address'])){header("location: index.php");}else{

//Inserting pet owners detaisl into table
	
$insertowner = "INSERT INTO users (uid, firstname, lastname, email_address, phone_number, password, city, state, zip, reg_date, recreq) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertowner);
$stmt->bind_param('sssssssssss', $uid, $_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['email_address'], $_SESSION['mobilenumber'], $_SESSION['password'], $_SESSION['city'], $_SESSION['state'], $_SESSION['zip'], $reg_date, $recreq);
if($stmt->execute()){$value = $value+1;
					
					 //Checking for user uploaded photo / using default image if user doesn't upload photo
					 if(isset($_SESSION['img'])){
					$default_image = $_SESSION['img'];
					 }else{$default_image = $photo_dir."default_pic.png";}
					 
					 //Setting vars for form submission
					 
					 //Default status of 1, status of 2 means pet is missing
					 $status = "1";
					 
					 //Intentionally empty for registration
					 $status_date = "";
//Inserting pet detaisl into table					 
$insertpet = "INSERT INTO pets (pid, uid, name, age, description, image, gender, status, status_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtt = $conn->prepare($insertpet);
$stmtt->bind_param('sssssssss', $pid, $uid, $_SESSION['petname'], $_SESSION['petage'], $_SESSION['description'], $default_image, $_SESSION['petgender'], $status, $status_date);
if($stmtt->execute()){$value = $value+1;}else{echo "There has been an error, return to registration <a href='registration.php'>Click Here</a>";}
$stmtt->close();
					 
					
					
					}else{echo "There has been an error, return to registration <a href='registration.php'>Click Here</a>";}
$stmt->close();

	//QR code generates if a value of 2 exists / completing registration.
if($value == 2){
	
	
	//QR code filename
	$filename = $pid."-qr.png";
	
	//Generating QR code using Google Devs/Charts
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."view/petid=".$pid);
	
	//Storing QR code
	file_put_contents("../".$qr_dir.$filename, $data);
}{
	
}
	
	}

	}


?>


<!doctype html>
<html>
<head>
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