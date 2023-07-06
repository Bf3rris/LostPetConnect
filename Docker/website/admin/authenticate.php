<?php

//Start mysqli connection
require('../connection.php');


//Posting / sanitizing login credentials
$email_address = strip_tags($_POST['email_address']);

//Encrypting password
$password = sha1(strip_tags($_POST['password']));


//Matching login credentials to authenticate user
$sql = "SELECT * FROM admin WHERE email_address = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email_address, $password);
if($stmt->execute()){$result = $stmt->get_result();
					 
					    //Row count containing result of match
						$count = $result->num_rows;
						}

//If results from credentials equals 1 access is granted
if ($count == 1){
		while($row = $result->fetch_assoc()){
			
			//Starting user session
session_start();
	
	//Setting session var for id
	$_SESSION['id'] = $row['id'];
}
	  
		
//Directing to dashboard after successful authentication
header("location: dashboard.php");}else{
	
	
	
//Setting login error if credentials don't match
$_SESSION['loginerror']	= "<font color='red'>Your login credentials do not match an active user.</font>";

//Directing to index/login page if unsuccessful
header("location: index.php");
	
	exit;
$stmt->close();

}

?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>