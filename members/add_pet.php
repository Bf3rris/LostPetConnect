<?php

//Start mysqli connection
require('../connection.php');

//Starting user session
session_start();


//Site settings config ID
$configid = "1";

//Query for site settings
$settings_sql = "SELECT website_title, domain, photo_dir, qr_dir FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $website_title = $array['website_title'];
					 $domain = $array['domain'];
					 $photo_dir = $array['photo_dir'];
					 $qr_dir = $array['qr_dir'];
					}
$stmt->close();





//Redirecting to login if user isn't logged in
if(isset($_SESSION['uid'])){}else{header("location: index.php");}

//Key is set in form to allow for input fields to be submitted / prevents unnecessary posting
if(isset($_POST['key'])){

//CHecking for existing petid to 
if(strip_tags($_POST['petid'] == "")){


	//Posting / sanitizing input fields to create new pet
    $name = strip_tags($_POST['name']);
	$age = strip_tags($_POST['age']);
	$timenoun = strip_tags($_POST['timenoun']);
	$gender = strip_tags($_POST['gender']);
	$description = strip_tags($_POST['description']);
	
	//Using default image until user uploads personal photo
	$default_image = $photo_dir."default_pic.png";
	
	//Generating new petid
	$pid = substr(str_shuffle(md5(date('his'))), 0, 8);

	//Setting var to keep columns mpty until user generates date
	$blank = "";
	$defaultstatus = "1";
	//Inserting new pet details in table
	$update = "INSERT INTO pets (pid, uid, name, age, timenoun, description, image, gender, status, status_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($update);
	$stmt->bind_param('ssssssssss', $pid, $_SESSION['uid'], $name, $age, $timenoun, $description, $default_image, $gender, $defaultstatus, $blank);
	if($stmt->execute()){
		//Holds success message for successful adding of pet to table
		$_SESSION['createstatus'] = "<font color='green'><strong>$name was added to your pets</strong></font>";
						 
						
											 
	//Filename of QR code
	$filename = $pid."-qr.png";
						 
						 
	//Domain or IP that hosts the webpage
						 
	//Call to Google Developers to generate QR code
	$data = file_get_contents('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$domain."/view/petid=".$pid);
						 
    //Storing QR code
	file_put_contents("../".$qr_dir.$filename, $data);
		
		//Setting status message for successful completion of adding pet
		$_SESSION['scstatus'] = "<font color='green'><strong>Pet was successfully added to My Pets.</strong></font>";
						
		//Redirecting to 'manage pets' after successful addition of pet to table
	header("location: manage_pet.php?petid=$pid");
	
						}
}
}elseif(
	//Allows for pet details to be edited if pet already exists
	isset($_POST['petid'])){
	
	//Setting var if pet already exists in database
	$currentid = strip_tags($_POST['petid']);
	
	//Posting / sanitizing user input of pet details
    $name = strip_tags($_POST['name']);
	$age = strip_tags($_POST['age']);
	$gender = strip_tags($_POST['gender']);
	$description = strip_tags($_POST['description']);
		

	//Updating pet details
	$uupdate = "UPDATE pets SET name = ?, age = ?, description = ?, gender = ? WHERE pid = ? AND uid = ?";
	$stmt = $conn->prepare($uupdate);
    $stmt->bind_param('ssssss', $name, $age, $description, $gender, $currentid, $_SESSION['uid']);
	
	if($stmt->execute()){
		
		//Holds success message for successful update of pet details
		$_SESSION['petupdated'] = "<font color='green'>Pet has been updated</font>";
						
						}
	$stmt->close();
	
	
	
}else{}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Add a New Pet</title>
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
      <td align="center"><h2><?php echo $website_title; ?></h2></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="50">&nbsp;</td>
      <td align="center"><h3>Add new Pet</h3>
		
		  <?php
		  //Displayed on successful update
		  if(isset($_SESSION['createstatus'])){
			  echo $_SESSION['createstatus'];
			  unset($_SESSION['createstatus']);
		  }
	
		  //Displaying invalid image file error message 
		  
		  if(isset($_SESSION['inavlidfile'])){echo $_SESSION['invalidfile']; unset($_SESSION['invalidfile']);}
		  
   //Displaying empty upload status error message
		  	 if(isset($_SESSION['nofile'])){echo $_SESSION['nofile'];
											
											//Unsetting error message
											 unset($_SESSION['nofile']);
											}
		  
		  
		  ?>
		
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">
		
		<?php require('navigation.php'); ?>

		
		
		</td>
      <td rowspan="2" align="center" valign="top">

		
		  
		  
		  <table width="500" border="1" bordercolor="lightgray" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
		  
		  <!----------Form for photo upload starts here---------->
		<form action="upload.php" method="post" enctype="multipart/form-data">
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
	  <tr>
	  <td align="center">
		 Photo
		  
		  </td><td></td>
		  <td align="center">&nbsp;</td>
	  </tr>
    <tr>
      <td width="180" rowspan="5" align="center">
		  <?php
		  //Displays photo of pet if exists
		  if(isset($_SESSION['imgplc'])){echo "<img src='$_SESSION[imgplc]'>";
									   unset($_SESSION['imgplc']);
									   
									   }else{
			  
			  //Displays default image if user photo doesn't exist
			  echo "<img src='../$photo_dir/default_pic.png' width='150' height='150'>";}
		  ?>
			  </td>
      <td width="313">&nbsp;</td>
      <td width="107">&nbsp;</td>
    </tr>
    <tr>
      <td><input type="file" name="photo"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="hidden" name="key" value="aap"></td>
    </tr>
    <tr>
      <td><input type="submit" value="Upload Photo"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
			  </form>
		<!----------Form for photo upload ends here---------->
		
		</td>
    </tr>
  </tbody>
</table>

		  
		  <p></p>
		  
		
		  	<table width="500" cellpadding="0" cellspacing="0">
	<tbody>
		   <tr>
		<td height="36" colspan="4" align="left">Pet details</td>
		</tr>
		   </tbody>
	</table>
		  <table width="500" border="1" cellpadding="0" cellspacing="0" bordercolor="lightgray" bordercellspacing="0">
  <tbody>
    <tr>
      <td>
				<!----------Form for pet details starts here---------->
<form action="add_pet.php" method="post" name="form">
		  
	
		  <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="122">Name</td>
      <td width="10">&nbsp;</td>
      <td width="199"><input type="text" name="name"></td>
      <td width="53">&nbsp;</td>
      <td width="53">&nbsp;</td>
      <td width="53">&nbsp;</td>
      <td width="53">&nbsp;</td>
      <td width="57">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Gender</td>
      <td>&nbsp;</td>
      <td>
		  
		<select name="gender">
			<option value="Male">Male</option>
			<option value="Female">Female</option>
			</select>
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Age</td>
      <td>&nbsp;</td>
      <td><input name="age" type="number" max="99" min="0">
		<select name="timenoun">
		  <option value="Months">Months</option>
			<option value="Years">Years</option>
		  </select>
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Description</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="5" rowspan="4">
		
		<textarea name="description" cols="50" rows="15"></textarea>
		
		</td>
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
    <tr>
      <td>&nbsp;</td>
      <td><input type="hidden" name="key" value="aap"></td>
      <td><input type="hidden" name="petid" value="<?php
		  
		  //Used to pass pet id to update pet details on submission
		  
		  if(isset($_SESSION['pid'])){
		  echo $_SESSION['pid']; unset($_SESSION['pid']);
		  }
		  ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><a href="my_pets.php"><input type="button" value="Exit without Saving"></a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="Add pet"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
		  </form>
		  <!----------Form for pet details ends here---------->
		
		
		</td>
    </tr>
  </tbody>
</table>

		  

		  
		</td>
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