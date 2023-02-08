<?php

session_start();

//Start MySQLi connection
require('../connection.php');

//Requesting pet id from query
$petid = strip_tags($_REQUEST['petid']);


//request uri
$uri = explode("=", $_SERVER['REQUEST_URI']);


//Retrieving owners uid to use in following query / retrieving details from row to display on webview
$owner = "SELECT uid, name, age, image, description, gender FROM pets WHERE pid = ?";
$stmt = $conn->prepare($owner);
$stmt->bind_param('s', $uri[1]);
if($stmt->execute()){$result = $stmt->get_result();
					$fetch = $result->fetch_assoc();
					 $uid = $fetch['uid'];
					$petname = $fetch['name'];
					 $petage = $fetch['age'];
					 $image = $fetch['image'];
					 $description = $fetch['description'];
					 $gender = $fetch['gender'];
					}

$stmt->close();

//Query for pet and user data
$selection = "SELECT firstname, lastname, city, state, zip FROM users WHERE uid = ?";
$stmt = $conn->prepare($selection);
$stmt->bind_param('s', $uid);
if($stmt->execute()){$result = $stmt->get_result();

					$data = $result->fetch_assoc();
				$firstname = $data['firstname'];
					 $lastname = $data['lastname'];
					 $city = $data['city'];
					 $state = $data['state'];
					 $zip = $data['zip'];
					}
$stmt->close();


if($uri[2] == "gencode"){
$code = str_shuffle(rand(111111, 999999));
$ph = "";

$date = date("m-d-y");
	$time = date('h:i.s');
$calllog = "INSERT INTO call_log (date, request_time, uid, from_number, code, pid, request_ip) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($calllog);
$stmt->bind_param('sssssss', $date, $time, $uid, $ph, $code, $uri[1], $_SERVER['REMOTE_ADDR']);
$stmt->execute();
$stmt->close();
}


?>

<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title>Lost Pet Connect</title>
<style type="text/css">
body,td,th {
	font-family: Tahoma;
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
	
	<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="red">
  <tbody>
    <tr>
	  <td align="center"><font color="#ffffff">This pet has been marked as lost by owners!</font></td>
    </tr>
  </tbody>
</table

	<table width="500" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="500" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td align="center">&nbsp;
		

		
		</td>
    </tr>
    <tr>
      <td align="center">
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td align="center">
		
		<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="149" align="center">
        
        
        <img src="../<?php echo $image; ?>" width="120" height="120">
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
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
		
		<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="87">&nbsp;</td>
      <td width="123">&nbsp;</td>
      <td width="266" align="center">&nbsp;</td>
      <td width="161">&nbsp;</td>
      <td width="63">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td align="center"></td>
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
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
		  
		
		  <table width="500" border="0" cellspacing="0" cellpadding="1" background="../images/tab_bg.png">
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
        
        <table width="500" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="50%">
                
                <table width="250" border="1" bordercolor="#000000" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td>
                        
                        <table width="250" border="0" cellspacing="0" cellpadding="0">
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
              <td width="50%" valign="top">
                
                <table width="250" border="1" bordercolor="#000000" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td>
                        
                        <table width="250" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="15" bgcolor="green">&nbsp;</td>
                              <td width="8" bgcolor="green">&nbsp;</td>
                              <td colspan="4" align="center" bgcolor="green"><font color="#ffffff">Contact</font></td>
                              <td width="14" bgcolor="green">&nbsp;</td>
                              <td width="23" bgcolor="green">&nbsp;</td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td colspan="2" align="center">Call</td>
                              <td width="13">&nbsp;</td>
                              <td width="8">&nbsp;</td>
                              <td width="86" align="center">Text</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td colspan="3" rowspan="4" align="center"><a href="tel:+19374539293"><img src="../images/call.png"></a></td>
                              <td>&nbsp;</td>
                              <td colspan="2" rowspan="4" align="center"><a href="sms:+19374539293?body=Your+pet+has+been+found:+<?php echo $uri[1];?><!>DoNotEditThisMsg<!>"><img src="../images/text.png"></a></td>
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
                              <td>&nbsp;</td>
                              <td width="83">&nbsp;</td>
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

	<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
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
      <td><?php if($code != ""){echo $code;} ?></td>
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