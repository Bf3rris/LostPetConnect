<?php
//Start MySQLi connection
require('../connection.php');

//Starting session
session_start();


//Directing to login if not logged in
if($_SESSION['uid'] == ""){header("location: index.php");}


//Retrieve owners personal details
$retrieve = "SELECT firstname FROM users WHERE uid =?";
$stmt = $conn->prepare($retrieve);
$stmt->bind_param('s', $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$data = $result->fetch_assoc();
					 $firstname = $data['firstname'];
					}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lost Pet Connect - Dashboard</title>
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
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><h2>Welcome,
		  <?php
		  
		  //Greeting owner by firstname
		  echo $firstname;
		  
		  ?>
		  </h2></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
		
		
		<?php  
		
		  
		 
		  //Counting # of users pets
		  $petcount = "SELECT pid FROM pets WHERE uid = ?";
		  $stmt = $conn->prepare($petcount);
		  $stmt->bind_param('s', $_SESSION['uid']);
		  if($stmt->execute()){$result = $stmt->get_result();
							   $count = $result->num_rows;
							  }
		  $stmt->close();
							   
							   if($count < 0){echo "You have no pets in the database";}else{echo "You have $count pets in the database";}
							   
		  ?>
		  
		
		
		
		</td>
    </tr>
  </tbody>
</table>

		
		
		</td>
      <td height="61">&nbsp;</td>
    </tr>
    <tr>
      <td height="400">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">LPC;</td>
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