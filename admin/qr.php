<?php

//Starting MySQL connection
require('../connection.php');

//Setting var with pet id
$petid = strip_tags($_REQUEST['petid']);

//Site settings config ID
$configid = "1";

//Query for website domain / title 
$settings_sql = "SELECT website_title, domain FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding url of site
					 $domain = $array['domain'];
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					
					}
$stmt->close();




?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	
	
<?php
	
//Displaying QR code
echo "
<img src='../images/qr/$petid-qr.png'>
";
	?>
</body>
</html>