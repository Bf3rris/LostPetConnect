<?php

//Start mysqli connection
require('connection.php');



//Site settings Config id
$configid = "1";

//Selecting website title from db
$site = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($site);
$stmt->bind_param('i', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 //
					 $count = $result->num_rows;
					 
					 if(isset($array['id'])){
					 //Var holding website title
				$website_title = $array['website_title'];
						 
					
					 }else{echo "This website has not been set up yet.<br />Lost Pet Connect";}
					}
$stmt->close();
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
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
	
	<center><h2><?php echo $website_title; ?></h2></center>
	<table width="700" border="1" bordercolor="#000000" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="240">&nbsp;</td>
      <td width="301">&nbsp;</td>
      <td width="159">&nbsp;</td>
    </tr>
    <tr>
      <td><img src="images/images/default_pic.png" width="230" height="200"></td>
      <td valign="top">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><h2><?php echo $website_title; ?></h2></td>
    </tr>
    <tr>
      <td>Give your pet an extra layer of protection with our unique identification system.<br />Create an ID for your pet that can be updated 24/7.</td>
    </tr>
    <tr>
      <td><h2>Features</h2></td>
    </tr>
	 <tr>
	  <td>✔ Dog Approved ✔ Cat Approved</td>
	  </tr>
	  <tr>
	  <td>&bull; Updatable Anytime &bull; Free</td>
	  </tr>
  </tbody>
</table>

		
		</td>
      <td valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr></tr>
        </tbody>
        <tbody>
          <tr>
            <td align="center"><a href="/members">Login Now</a></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><a href="/registration">Create Account</a></td>
          </tr>
        </tbody>
        <tbody>
        </tbody>
      </table></td>
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