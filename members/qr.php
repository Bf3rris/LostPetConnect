<?php

//Starting MySQL connection
require('../connection.php');

//Setting var with pet id
$petid = strip_tags($_REQUEST['petid']);

//Site settings config ID
$configid = "1";

//Query for website domain / title / qr code dir
$settings_sql = "SELECT website_title, domain FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding site url
					 $domain = $array['domain'];
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					}
$stmt->close();


//Displaying QR code
echo "
<img src='$domain/images/qr/$petid-qr.png'>
";


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