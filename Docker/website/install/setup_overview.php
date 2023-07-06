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
if(empty($_POST['mysql_location']) || empty($_POST['database_name']) || empty($_POST['mysql_username']) || empty($_POST['mysql_password'])){$_SESSION['emptyinput'] = "All fields must be completed.";

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
	echo "Connection to MySQL server: Successful <br />";
	
	//Attempt to select DB that tables will be created on
	if(mysqli_select_db($conn, $_SESSION['database_name'])){
		
		//Displaying message upon successful selection of database
		echo "Database selected: $_SESSION[database_name]: Successful<br />";
	
		
			
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
 ) ")){echo "Admin table creation: Successful <br />";
	
	$value = $value+1;
	}else{echo "<font color='#ff0000'><strong>Admin table creation: Failed</strong></font>. Check MySQL user has 'CREATE' privilege and try again.<br />";}
 		
//Inserting admin user account into admin table
		
		//Query for existing admin user / if user partially completed install
		require('../connection.php');
		$row_count = "SELECT id FROM admin";
		$stmt = $conn->prepare($row_count);
		if($stmt->execute()){$result = $stmt->get_result();
							$rowcount = $result->num_rows;
							}
		$stmt->close();
		
		//Function to insert admin user if there are none existing in database
		if($rowcount != 0){echo "Admin user has already been created.";}else{
		$admin_insert = "INSERT INTO admin (first_name, last_name, email_address, password, recreq) VALUES ('$_SESSION[firstname]', '$_SESSION[lastname]', '$_SESSION[email_address]', '$_SESSION[password]', '$recreq')";
		if(mysqli_query($conn, $admin_insert)){echo "Admin user account creation: Successful <br />";
											  $value = $value+1;
											  }else{echo "<font color='#ff0000'><strong>Admin user account creation: Failed. </strong></font>Check MySQL user has 'INSERT' privilege and try again.<br />";}
				
		}
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
recreq VARCHAR(40),
restricted LONGTEXT,
call_out VARCHAR(13)
) ")){echo "Users table creation: Successful <br />";
	 $value = $value+1;
	 
	 }else{echo "<font color='#ff0000'>'Users' table creation: Failed.</strong></font>Check MySQL user has 'CREATE' privilege and try again.<br />";}

//Creating pets table
 if(mysqli_query($conn, "CREATE TABLE pets(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 pid VARCHAR(18), 
 uid VARCHAR(40),
 name VARCHAR(24),
 age VARCHAR(3),
timenoun VARCHAR(7),
 description VARCHAR(500),
 image VARCHAR(64),
 gender VARCHAR(7),
status VARCHAR(1),
status_date VARCHAR(16)
 ) ")){echo "Pets table creation: Successul. <br />";
	  $value = $value+1;
	  }else{echo "<font color='#ff0000'><strong>'Pets' table creation: Failed.</strong></font> Check MySQL user has 'CREATE' privilege and try again.<br />";}

		//Creating website settings table
  if(mysqli_query($conn, "CREATE TABLE site_settings(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 website_title VARCHAR(64), 
domain VARCHAR(32),
support_email VARCHAR(32),
composer_path VARCHAR(64),
xfn VARCHAR(12),
space_url VARCHAR(64),
project_id VARCHAR(64),
token VARCHAR(64)
 ) ")){echo "'Site_settings' table creation: Successful<br />";
	  $value = $value+1;
	  }else{echo "<font color='#ff0000'><strong>'Site_settings' table creation: Failed</strong></font> Check MySQL user has 'CREATE' privilege and try again.<br />";}
		
		//Checking for existing site settings config (usually if an incomplete install exists
		$ssq = "SELECT id FROM site_settings";
		$stmt = $conn->prepare($ssq);
		if($stmt->execute()){$result = $stmt->get_result();
							$ssqc = $result->num_rows;
							}
		$stmt->close();
		if($ssqc != 0){echo "Site settings already exists.";}else{
//Inserting website settings inputted during setup into table
			
			
$site_settings = "INSERT INTO site_settings (website_title, domain, support_email,  composer_path, xfn, space_url, project_id, token) VALUES ('$_SESSION[website_title]', '$_SESSION[domain]', '$_SESSION[contact]', '$_SESSION[composer_path]', '$_SESSION[xfn]','$_SESSION[space_url]', '$_SESSION[pid]', '$_SESSION[token]')";
if(mysqli_query($conn, $site_settings)){echo "'site_settings' data insertion: Successful <br />";
									   
									   $value = $value+1;
									   }else{echo "<font color='#ff0000'><strong>'site_settings' data insertion: Failed.</strong></font> Check MySQL user for 'INSERT' privilege.<br />";}
		}
//Creating call log table
 if(mysqli_query($conn, "CREATE TABLE call_log(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 date VARCHAR(18), 
 request_time VARCHAR(18),
 uid VARCHAR(20),
out_id VARCHAR(64),
from_number VARCHAR(13),
code VARCHAR(8),
call_id VARCHAR(50),
pid VARCHAR(8),
request_ip VARCHAR(18),
ics VARCHAR(24),
icamd VARCHAR(9),
notes VARCHAR(200),
tag VARCHAR(50),
active_call VARCHAR(1)
 ) ")){echo "Call log table creation: Successful<br />";
	  
	   $value = $value+1;
	  }else{echo "<font color='#ff0000'><strong>'Call_log' table creation: Failed.</strong></font> Check MySQL user has 'CREATE' privilege and try again.<br />";}

//Creating message log table
 if(mysqli_query($conn, "CREATE TABLE message_log(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 mid VARCHAR(128), 
 from_number VARCHAR(13),
 uid VARCHAR(18),
 petid VARCHAR(8),
body VARCHAR(320),
time VARCHAR(20),
date VARCHAR(12),
notes VARCHAR(200)
 ) ")){echo "'Message_log' table creation: Successful <br />";
	  
	  $value = $value+1;
	   
	   if($value == "8"){echo "<strong>Setup has finished sucessfully.</strong>";}else{echo "There was an error during installation. This is due to your MySQL database.";}
	  }else{echo "<font color='#ff0000'><strong>'Message_log' table creation: Failed.</strong></font> Check MySQL user has 'CREATE' privilege and try again.<br />";}
	/*
//creating .htaccess file / storing in 'view' directory
$htaccess = "../view/.htaccess";
$fh = fopen($htaccess, 'w') or die("Error creating htaccess file. Check user has proper 'write' permissions and try again.");
fwrite($fh, 'RewriteEngine on'."\n");
fwrite($fh, ''."\n");
fwrite($fh, 'RewriteRule .* view.php');
fclose($fh);
	
	*/	
		
}else{
		
//if unsuccessful at selecting db
echo "<font color='#ff0000'><strong>Database selection: $_SESSION[database_name]: Failed.</strong></font> Check database exists <br />/ check MySQL user has has 'SELECT' privilege.";}
	
	
}else{
	
	//If mysql connection is unsuccessful error message will generate
	echo "<font color='#ff0000'><strong>MySQL database connection: Failed</strong></font>";}
//mysqli_select_db($conn, $_SESSION['database_name']);
	

}
?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
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
