<?php

//Starting mysql connection
require('../connection.php');

//Starting user session
session_start();

//Checking session for logged in status
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Setting session var with pet id until removal occurs
if(isset($_SESSION['resetid'])){}else{$_SESSION['resetid'] = strip_tags($_REQUEST['petid']);}

	
//retrieving photo from db before deletion
	$datareq = "SELECT uid, name, image FROM pets WHERE pid = ?";
	$stmt = $conn->prepare($datareq);
	$stmt->bind_param('s', $_SESSION['resetid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 
						 //Var holding name of pet 
						 $name = $array['name'];
						 
						 //Var holding directory location of image of pet
						 $_SESSION['image'] = $array['image'];
						 
						 //Var holding uid of owner of pet
						 $uid = $array['uid'];
						}
	$stmt->close();



//Setting var to confirm action for pet removal
if(isset($_POST['confirm'])){$confirm = strip_tags($_POST['confirm']);

//Function to confirm removal set b
if($confirm == "Yes"){
	

	
	//unlink old qr code
	unlink("../images/qr/".$_SESSION['resetid']."-qr.png");
	
	
	//Generating new petid
	$pid = substr(str_shuffle(md5(date('his'))), 0, 8);
	
		
	
	//getext
	$ext = explode(".", $_SESSION['image']);
	
	//Var holding new filename of pets photo
	$image = $pid.".".$ext[1];
	
	
	//Renaming pet image filename to match new id
		rename("../images/images/".$_SESSION['image'], "../images/images/".$pid.".".$ext[1]);
	
	
	//Updating pet table with new pet id
	$idupdate = "UPDATE pets SET pid = ?, image = ? WHERE pid = ?";
	$stmt = $conn->prepare($idupdate);
	$stmt->bind_param('sss', $pid, $image, $_SESSION['resetid']);
	if($stmt->execute()){
		
			//Call to Google Developers to generate new QR code
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."/view/petid=".$pid);
	
	//Storing new QR code
	file_put_contents("../images/qr/".$pid."-qr.png", $data);
		
		
		//Setting session var for update status
		$_SESSION['completestatus'] = "<font color='green'><strong>ID Change has completed.</strong></font>";
	//Unsetting old session pet id
		unset($_SESSION['resetid']);
		
		//
		header("location: user_view.php?uid=$uid");
			
	unset($_SESSION['image']);
	}else{$_SESSION['completestatus'] = "<font color='#ff0000'><strong>There was an error completing the ID change.</strong></font>";
		 	//Unsetting old session pet id

		 		unset($_SESSION['resetid']);

		 }
	$stmt->close();
	
}
}

//Site settings config id
$configid = "1";

//Selecting website title from seite settings table
$settings_sql = "SELECT domain, website_title FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					 //Var holding site url
					 $domain = $array['domain'];
					}
$stmt->close();


?>



<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
	<title><?php echo $website_title; ?> - Manage Pet / Generate New Pet Identity</title>
	
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
<style type="text/css">
body,td,th {
	font-family: Arial;
}
</style>
</head>

<body>
	
	
	<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td valign="top">
		
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
      <td align="center"><h3>Manage Users / Reset Pet ID</h3>
		
		
		  
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">

		
		  
		  
		  <table width="450" border="0" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
	  
	  
	  <tr>
	 <td>This feature will change the Pet ID and QR code associated with this pet. This will not delete images or user data.: <i><strong><i><?php echo $name; ?></i></strong></td>
	  </tr>
    <tr>
      <td align="center">
		  
		  <!------------Start of confirmation form ------------>
		
		  <table width="450" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><form action="generate_id.php" method="post">
		<table width="450" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>      
	    <td colspan="6">&nbsp;</td>
	    </tr>
    <tr>
      <td colspan="6" align="center"><h2>Continue?</h2></td>
      </tr>
    <tr>
      <td width="29"><input type="hidden" name="complete" value="<?php if(isset($_SESSION['resetid'])){echo $_SESSION['resetid'];} ?>"></td>
      <td width="124" align="center"><input type="hidden" name="petid" value="<?php if(isset($_SESSION['resetid'])){echo $_SESSION['resetid']; } ?>"></td>
      <td width="134" align="center"><input type="submit" name="confirm" value="Yes"></td>
		<td width="135" align="center"><a href="user_mgmt.php"><input type="button" value="Cancel"></a></td>
      <td width="149">&nbsp;</td>
      <td width="29">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
      </tr>
  </tbody>
</table>
			  </form></td>
    </tr>
  </tbody>
</table>

		  
		<!------------End of confirmation form ------------>
		</td>
    </tr>
  </tbody>
</table>

		  
		  <p></p></td>
    </tr>
    <tr>
      <td height="299" align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>

	
		
		</td>
      <td height="990">&nbsp;</td>
    </tr>
    </tbody>
</table>

		
		</td>
    </tr>
  </tbody>
</table>

	
	

</body>
</html>