<?php

//Starting mysql connection
require('../connection.php');

//Starting user session
session_start();

//Checking session for logged in status
if(isset($_SESSION['id'])){}else{header("location: index.php");}


//Setting session var with pet id until removal occurs
if(is_null($_SESSION['resetid'])){$_SESSION['resetid'] = strip_tags($_REQUEST['petid']);}
//if($_SESSION['rmid'] == ""){header("location: dashboard.php");}
//retrieving photo from db before deletion
	$datareq = "SELECT uid, name, image FROM pets WHERE pid = ?";
	$stmt = $conn->prepare($datareq);
	$stmt->bind_param('s', $_SESSION['resetid']);
	if($stmt->execute()){$result = $stmt->get_result();
						$array = $result->fetch_assoc();
						 $name = $array['name'];
						 $_SESSION['image'] = $array['image'];
						 $uid = $array['uid'];
						}
	$stmt->close();



//Setting var to confirm action for pet removal
if(isset($_POST['confirm'])){$confirm = strip_tags($_POST['confirm']);

//Function to confirm removal set b
if($confirm == "Yes"){
	
	$configid = "1";
	$urlsql = "SELECT domain, photo_dir, qr_dir FROM site_settings WHERE id = ?";
	$stmt = $conn->prepare($urlsql);
	$stmt->bind_param('s', $configid);
	if($stmt->execute()){$result = $stmt->get_result();
					   $array = $result->fetch_assoc();
					   $domain = $array['domain'];
					   $qr_dir = $array['qr_dir'];
						 $photo_dir = $array['photo_dir'];
					   }
	
	$stmt->close();

	
	//unlink old qr code
	unlink("../".$qr_dir.$_SESSION['resetid']."-qr.png");
	
	
	//Generating new petid
	$pid = substr(str_shuffle(md5(date('his'))), 0, 8);
	
		
	
	//getext
	$ext = explode(".", $_SESSION['image']);
	
	//Renaming pet image filename to match new id
		rename("../".$_SESSION['image'], "../".$photo_dir.$pid.".".$ext[1]);
	
	//setimage
	$image = $photo_dir.$pid.".".$ext[1];
	
	//Updating pet table with new pet id
	$idupdate = "UPDATE pets SET pid = ?, image = ? WHERE pid = ?";
	$stmt = $conn->prepare($idupdate);
	$stmt->bind_param('sss', $pid, $image, $_SESSION['resetid']);
	if($stmt->execute()){
		
			//Call to Google Developers to generate new QR code
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."/view/petid=".$pid);
	
	//Storing new QR code
	file_put_contents("../".$qr_dir.$pid."-qr.png", $data);
		
		
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


$configid = "1";
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();


?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Manage Pet / Remove Pet</title>
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
	 <td>This feature will change the Pet ID and QR code associated with this pet: <i><strong><i><?php echo $name; ?></i></strong></td>
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