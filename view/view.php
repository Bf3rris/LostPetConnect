<?php

//Starting user session
session_start();

//Starting MySQL connection
require('../connection.php');

//Setting pet id var
if(isset($_POST['petid'])){$petid = strip_tags($_REQUEST['petid']);}

//Retrieving parameters from URL
$uri = explode("=", $_SERVER['REQUEST_URI']);



//Retrieving owners uid to use in following query / retrieving details from row to display on webview
$owner = "SELECT * FROM pets WHERE pid = ?";
$stmt = $conn->prepare($owner);
$stmt->bind_param('s', $uri[1]);
if($stmt->execute()){$result = $stmt->get_result();
					$fetch = $result->fetch_assoc();
					 
					 //Var holding pet owners uid
					 $uid = $fetch['uid'];
					 
					 //Var holding pet name
					$petname = $fetch['name'];
					 
					 //Var holding pet age
					 $petage = $fetch['age'];
					 
					 //Var holding pet image path
					 $image = $fetch['image'];
					 
					 //Var holding description of pet
					 $description = $fetch['description'];
					 
					 //Var holding pet gender
					 $gender = $fetch['gender'];
					 
					 //Var holding safety status safe (1) or missing (2) status
					 $status = $fetch['status'];
					 
					 //Var holding missing status date
					 $status_date = $fetch['status_date'];
					}

$stmt->close();

//Querying db for pet and user data to display on webview
$selection = "SELECT firstname, lastname, city, state, zip FROM users WHERE uid = ?";
$stmt = $conn->prepare($selection);
$stmt->bind_param('s', $uid);
if($stmt->execute()){$result = $stmt->get_result();

					$data = $result->fetch_assoc();
					 
					 //Var holding first name of pet owner
				$firstname = $data['firstname'];
					 
					 //Var holding last name of pet owner
					 $lastname = $data['lastname'];
					 
					 //Var holding city location of pet owner
					 $city = $data['city'];
					 
					 //Var holding state of pet owner
					 $state = $data['state'];
					
					 //Var holding zip code of pet owner
					 $zip = $data['zip'];
					}
$stmt->close();

//Checking URL parameter for keyword to generate call code


//If parameter 2 is set and == gencode a call code will be generated 
//Ccall code is necessary for calls to be placed and directed

if(isset($uri[2])){
if($uri[2] == "gencode"){
	
	//Creating random 6 digit call code
$code = str_shuffle(rand(111111, 999999));
	
	//Intentionally blank 
$blank = "";

//Setting date var to use for call log 
$date = date("m.d.y");
	
	//Setting time var for use in call log
	$time = date('h:i.s');
	
	
$calllog = "INSERT INTO call_log (date, request_time, uid, out_id, from_number, code, call_id, pid, request_ip, ics, icamd, notes, tag) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($calllog);
$stmt->bind_param('sssssssssssss', $date, $time, $uid, $blank, $blank, $code, $blank, $uri[1], $_SERVER['REMOTE_ADDR'], $blank, $blank, $blank, $blank);
$stmt->execute();
$stmt->close();
}

}
//Site settings config ID
$configid = "1";

//Query for site settings / website title and transferring phone number
$settings_sql = "SELECT website_title, xfn FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 
					 //Var holding website title
					 $website_title = $array['website_title'];
					 
					 //Var holding SignalWire phone number
					 $xfn = $array['xfn'];
					 
					}
$stmt->close();



?>

<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo "$website_title - $petname &middot; $city, $state"; ?> - Lost Pet Connect</title>
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

<body><center>
	<?php
	if($status == "2"){echo "
	<table width='410' border='0' align='center' cellpadding='0' cellspacing='0' bgcolor='red'>
  <tbody>
    <tr>
	  <td align='center'><h1><font color='#ffffff'>M I S S I N G!</font></h1></td>
	  </tr>
	  <tr>
	  <td align='center'><font color='#ffffff'><strong>Last updated: $status_date</strong></font></td>
    </tr>
  </tbody>
</table";
					  }
?>
	</center>
	<table width="410" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="410" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
		
		<table width="410" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center">
        
        <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="149" align="center">
                
                
                <img src="../images/images/<?php echo $image; ?>" width="120" height="120">
                </td>
              <td width="150" valign="top">
                <table width="150" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td><strong>Name</strong></td>
                      </tr>
                    <tr>
                      <td><?php echo $petname . "&nbsp;<small>($gender)</small>"; ?></td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      </tr>
                    <tr>
                      <td><strong>Age</strong></td>
                      </tr>
                    <tr>
                      <td><?php echo "$petage years"; ?></td>
                      </tr>
                    </tbody>
                  </table>
                
                
                
                </td>
              </tr>
            </tbody>
          </table>
        
        
        
        
      </td>
      </tr>
    <tr>
      <td>
		
		<table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="87">&nbsp;</td>
      <td width="55"></td>
      <td width="100" align="center"></td>
      <td width="100">&nbsp;</td>
      <td width="55">&nbsp;</td>
    </tr>
  </tbody>
</table>

		
		</td>
      </tr>
  </tbody>
</table>

		
		</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
		  
		
		  <table width="410" border="0" cellspacing="0" cellpadding="1" background="../images/tab_bg.png">
        <tbody>
          <tr>
			  <td background="../images/top.png" style="background-repeat: no-repeat"><font color="#ffffff"><strong>&nbsp;&nbsp;About <?php echo $petname; ?></strong></font></td>
          </tr>
          <tr>
            <td><?php echo $description; ?></td>
          </tr>
			<tr>
			<td background="../images/tab_bottom.png" style="background-repeat: no-repeat"></td>
			</tr>
        </tbody>
      </table>
		 
		
		</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
        
        <table width="400" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="200">
                
                <table width="200" border="1" bordercolor="#000000" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td>
                        
                        <table width="200" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td bgcolor="#000fff"><font color="#ffffff">Owner information</font></td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td><strong>Name</strong></td>
                              </tr>
                            <tr>
                              <td>&bull; <?php echo $firstname ."&nbsp;" . $lastname; ?></td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td><strong>Location</strong></td>
                              </tr>
                            <tr>
                              <td><?php echo $city . ",&nbsp;" . $state . "&nbsp;($zip)"; ?></td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              </tr>
                            </tbody>
                          </table>
                        
                        </td>
                      </tr>
                    </tbody>
                  </table>
 </td>
              <td width="200" valign="top">
                
                <table width="200" border="1" bordercolor="#000000" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td>
                        
                        <table width="200" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="15" bgcolor="green">&nbsp;</td>
                              <td width="8" bgcolor="green">&nbsp;</td>
                              <td colspan="4" align="center" bgcolor="green"><font color="#ffffff">Contact</font></td>
                              <td width="14" bgcolor="green">&nbsp;</td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td colspan="2" align="center">Call</td>
                              <td width="13">&nbsp;</td>
                              <td width="8">&nbsp;</td>
                              <td width="86" align="center">Text</td>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td colspan="3" rowspan="4" align="center"><a href="tel:+<?php echo $xfn; ?>"><img src="../images/call.png"></a></td>
                              <td>&nbsp;</td>
                              <td colspan="2" rowspan="4" align="center"><a href="sms:+<?php echo $xfn; ?>?body=Your+pet+has+been+found:<?php echo $uri[1];?>"><img src="../images/text.png"></a></td>
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
                              <td width="83">&nbsp;</td>
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
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
				</td>
              
              </tr>
            </tbody>
          </table>
 </td>
    </tr>
    </tbody>
</table>
	<table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
		<td colspan="2"><u>Generate Call PIN:</u></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5">A Call PIN is required to place a call to the owner.</td>
      </tr>
    <tr>
      <td><a href="petid=<?php echo $uri[1] . '=gencode';?>">Generate code</a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><?php if(isset($code)){echo $code;} ?></td>
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
    </tr>
  </tbody>
</table>
	  
		  
		  
		
	  </td>
    </tr>
  </tbody>
</table>

	
	
	
</body>
</html>