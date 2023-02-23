<?php

//Startting session
session_start();

//Value flag for completion options
$value = 0;

//Initial password reset column/key
$recreq = sha1(rand(0000,9999));

//Checking for data from form to allow posting // prevents unnecessary submissions
if(strip_tags($_POST['formref'] == "ms")){
	
	
//Checking for empty fields 
if(empty($_SESSION['mysql_location']) || empty($_SESSION['database_name']) || empty($_SESSION['mysql_username']) || empty($_SESSION['mysql_password'])){$_SESSION['emptyinput'] = "All fields must be completed.";

//Redirecting back to proper page if empty fields exist
header("location: mysql_setup.php"); exit;
}	
	
	
	
//Creating session vars with website settings / sanitizing input
$_SESSION['mysql_location'] = strip_tags($_POST['mysql_location']);
$_SESSION['database_name'] = strip_tags($_POST['database_name']);
$_SESSION['mysql_username'] = strip_tags($_POST['mysql_username']);
$_SESSION['mysql_password'] = strip_tags($_POST['mysql_password']);

}elseif(strip_tags($_POST['formref'] == "complete")){
	
//Testing MySQL connection using user input
$mysql_location = strip_tags($_SESSION['mysql_location']);
$mysql_username = strip_tags($_SESSION['mysql_username']);
$mysql_password = strip_tags($_SESSION['mysql_password']);
	
	//checking connection
if(mysqli_connect($mysql_location, $mysql_username, $mysql_password)){
	//setting connection var to use in next step
	$conn = mysqli_connect($mysql_location, $mysql_username, $mysql_password);
	
	//message upon sucessful mysql connection
	echo "Successful connection to MySQL server. <br />";
	
	//Attempt to select DB that tables will be created on
	if(mysqli_select_db($conn, $_SESSION['database_name'])){
		
		//Displaying message upon successful selection of database
		echo "Database selected, $_SESSION[database_name]<br />";
	
		
			
		//Creating / writing MySQL connection file / storing to view dir
		$config_filename = "../connection.php";
$fh = fopen($config_filename, 'w') or die("Error creating MySQL connection file. Check user has proper 'write' permissions and try again.");
fwrite($fh, '<?php'."\n".'//Required MySQL connection settings'."\n".'//DO NOT MODIFY'."\n");
fwrite($fh, '$mysql_location' . ' = ' . '"'.$_SESSION['mysql_location'] . '"'.";"."\n");
fwrite($fh, '$mysql_username' . ' = ' . '"'.$_SESSION['mysql_username']. '"' . ";"."\n");
fwrite($fh, '$mysql_password' . ' = ' . '"'.$_SESSION['mysql_password']. '"' . ";"."\n");
fwrite($fh, '$conn = mysqli_connect($mysql_location, $mysql_username, $mysql_password) or die (mysqli_error("Failed to connect"));'."\n");
fwrite($fh, 'mysqli_select_db($conn, ' . '"' . $_SESSION['database_name'] . '"'. ')' . ";"."\n");
fwrite($fh, '?>');
fclose($fh);

//Creating admin table
if(mysqli_query($conn, "CREATE TABLE admin(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
  first_name VARCHAR(24), 
  last_name VARCHAR(24),
email_address VARCHAR(32),
password VARCHAR(40),
recreq VARCHAR(40)
 ) ")){echo "Admin table sucessfully created. <br />";
	
	$value = $value+1;
	}else{echo "Admin table failed to create. Check MySQL user has 'CREATE' privilege and try again.<br />";}
 		
//Inserting admin user account into admin table
		$admin_insert = "INSERT INTO admin (first_name, last_name, email_address, password,recreq) VALUES ('$_SESSION[firstname]', '$_SESSION[lastname]', '$_SESSION[email_address]', '$_SESSION[password]', '$recreq')";
		if(mysqli_query($conn, $admin_insert)){echo "Admin user successfully created. <br />";
											  $value = $value+1;
											  }else{echo "Admin user failed to be created. Check MySQL user has 'INSERT' privilege and try again.<br />";}
				
		
//Creating users table
 if(mysqli_query($conn, "CREATE TABLE users(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
uid VARCHAR(40),
firstname VARCHAR(24),
lastname VARCHAR(24),
email_address VARCHAR(32),
phone_number VARCHAR(32),
password VARCHAR(40),
city VARCHAR(32),
state VARCHAR(3),
zip VARCHAR(5),
reg_date VARCHAR(24),
recreq VARCHAR(40)
) ")){echo "Users table successfully created. <br />";
	 $value = $value+1;
	 
	 }else{echo "Users table failed to create. Check MySQL user has 'CREATE' privilege and try again.<br />";}

//Creating pets table
 if(mysqli_query($conn, "CREATE TABLE pets(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 pid VARCHAR(18), 
 uid VARCHAR(40),
 name VARCHAR(24),
 age VARCHAR(3),
 description VARCHAR(500),
 image VARCHAR(64),
 gender VARCHAR(7),
status VARCHAR(1),
status_date VARCHAR(16)
 ) ")){echo "Pets table successfully created. <br />";
	  $value = $value+1;
	  }else{echo "Pets table failed to create. Check MySQL user has 'CREATE' privilege and try again.<br />";}

//Creating website settings table
  if(mysqli_query($conn, "CREATE TABLE site_settings(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 website_title VARCHAR(64), 
 domain VARCHAR(32),
 support_email VARCHAR(32),
 photo_dir VARCHAR(64),
qr_dir VARCHAR(64),
xfn VARCHAR(11)
 ) ")){echo "Site_settings table successfully created. <br />";
	  $value = $value+1;
	  }else{echo "Site_settings table failed to create. Check MySQL user has 'CREATE' privilege and try again.<br />";}
		
		
//Inserting website settings inputted during setup into table
$site_settings = "INSERT INTO site_settings (website_title, domain, support_email, photo_dir, qr_dir, xfn) VALUES ('$_SESSION[website_title]', '$_SESSION[domain]', '$_SESSION[contact]', '$_SESSION[photo_dir]', '$_SESSION[qr_dir]', '$_SESSION[xfn]')";
if(mysqli_query($conn, $site_settings)){echo "Website site settings successfully inserted into site_settings table. <br />";
									   
									   $value = $value+1;
									   }else{echo "Website settings failed to be inserted. Check MySQL user for 'INSERT' privilege.<br />";}

//Creating call log table
 if(mysqli_query($conn, "CREATE TABLE call_log(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 date VARCHAR(18), 
 request_time VARCHAR(18),
 uid VARCHAR(20),
 from_number VARCHAR(12),
code VARCHAR(8),
pid VARCHAR(8),
request_ip VARCHAR(18)
 ) ")){echo "Call log table successfully created.<br />";
	  
	   $value = $value+1;
	  }else{echo "Call_log table failed to create. Check MySQL user has 'CREATE' privilege and try again.<br />";}

//Creating message log table
 if(mysqli_query($conn, "CREATE TABLE message_log(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 mid VARCHAR(128), 
 locatorid VARCHAR(12),
 uid VARCHAR(18),
 petid VARCHAR(8),
body VARCHAR(320)
 ) ")){echo "Message log table successfully created. <br />";
	  
	  $value = $value+1;
	  }else{echo "Message_log table failed to create. Check MySQL user has 'CREATE' privilege and try again.<br />";}
	
//creating .htaccess file / storing in 'view' directory
$htaccess= "../view/.htaccess";
$fh = fopen($htaccess, 'w') or die("Error creating htaccess file. Check user has proper 'write' permissions and try again.");
fwrite($fh, 'RewriteEngine on'."\n");
fwrite($fh, ''."\n");
fwrite($fh, 'RewriteRule .* view.php');
fclose($fh);
	
		
		
}else{
		
//if unsuccessful at selecting db
echo "Unable to select database, $_SESSION[database_name]";}
	
	
}else{
	
	//If mysql connection is unsuccessful error message will generate
	echo "Unable to establish MySQL connection";}
//mysqli_select_db($conn, $_SESSION['database_name']);
	

}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lost Pet Connect - Registration</title>
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
	
	<table width="700" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="34">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="46">&nbsp;</td>
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td colspan="2" align="center"><h2>Lost Pet Connect</h2></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><h3>Installation Wizard</h3></td>
		<td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><strong><u>Complete install</u></strong></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="152">&nbsp;</td>
      <td width="268">&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="11" align="center" valign="top">
		  
		  
		  		  <!------Personal Details form starts  here---->

		<form action="setup_overview.php" method="post">
			
			<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="34">&nbsp;</td>
      <td colspan="2" align="center">&nbsp;</td>
      <td width="35">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center">Complete the install process by selecting '<strong>Complete install</strong>'. This final step will input all necessary tables and data into the database and create necessary environment files.</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><input type="hidden" name="formref" value="complete"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="216" align="center">
		  
		  
		  <?php
		  if($value == 8){echo "";}else{echo "
		  <a href='mysql_setup.php'>
        <input type='button' value='&larr; Previous'>
      </a>";}
		  ?></td>
      <td width="215" align="center">
		  
		  
		  
		  <?php
		  if($value == 8){echo "";}else{echo "
		  <input type='submit' value='Complete install'>";}
			  ?>
			  
			  </td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td colspan="4" align="center"> <?php
		  if($value == 8){echo "<img src='../images/success.png'> <br />
		  <a href='../admin'>Login to your Administrator account</a>"
		  ;}
			  ?> </td>
      </tr>
    </tbody>
</table>

			
			
			
		</form>
				  <!------Personal Details form ends  here---->

		
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
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
