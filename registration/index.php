<?php

//Starting MySQL connection
require('../connection.php');

//Startting session for form input / navigation
session_start();


//Site settings config ID
$configid = "1";

//Query for site settings
$settings_sql = "SELECT * FROM site_settings WHERE id = ?";
$stmt = $conn->prepare($settings_sql);
$stmt->bind_param('s', $configid);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					 $website_title = $array['website_title'];
					 
					}
$stmt->close();

?>

<!doctype html>
<html>
<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<title><?php echo $website_title; ?> - Registration</title>
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
	
	<table width="500" border="1" bordercolor="lightgray" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
		
		<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="34">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="46">&nbsp;</td>
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td colspan="2" align="center"><h2><?php echo $website_title; ?></h2></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><h3>Registration</h3></td>
		<td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" align="center"><strong><u>Personal Information</u></strong> Step1 of 3</td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><hr width="400" color="lightgray"></td>
      <td align="center">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">Complete the fields below to create your account.</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="152">&nbsp;</td>
      <td width="268">
		<?php
		  
		  //Displaying error message if first name field is blank
		  if(isset($_SESSION['firstnameerror'])){echo $_SESSION['firstnameerror'];
											   //Unsetting blank first name error message
												unset($_SESSION['firstnameerror']);
											   } 
		  ?>
		  
		
		</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="11" valign="top">
		  
		  
		  		  <!------Personal Details form starts  here---->

		<form action="pet_details.php" method="post">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>First name</td>
      <td width="2%">&nbsp;</td>
      <td width="38%"><input name="firstname" type="text" value="<?php
		  
		  //Displaying first name if exists as session variable
		  if(isset($_SESSION['firstname'])){echo $_SESSION['firstname'];}?>" size="24" maxlength="24"></td>
      <td>&nbsp;</td>
      <td width="1%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php
		  
		  //Displaying error message if last name field is blank
		  if(isset($_SESSION['lastnameerror'])){echo $_SESSION['lastnameerror'];
													 //Unsetting error message
												unset($_SESSION['lastnameerror']);
											   }
		  	?>																								
	      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Last name</td>
      <td>&nbsp;</td>
      <td><input name="lastname" type="text" value="<?php
		  
		  //Displaying last name if set as session variable
		  if(isset($_SESSION['lastname'])){echo $_SESSION['lastname'];}?>" size="24" maxlength="24"></td>
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
    <tr>
      <td colspan="4">
        <?php
		  
		  //Displaying error message if attempted signup email address is in use by another member
			if(isset($_SESSION['emailexistserror'])){echo $_SESSION['emailexistserror'];
											 //Unset email in use error
											  unset($_SESSION['emailexistserror']);
											 }
		  //Displaying error messageif email address field is blank 
			if(isset($_SESSION['emailerror'])){echo $_SESSION['emailerror'];
											 //Unset blank email address error message
											  unset($_SESSION['emailerror']);
											 }
		  
		 									 //Displaying error message if email address appears to be invalid
											  if(isset($_SESSION['invalidemail'])){echo $_SESSION['invalidemail'];
											 //Unset invalid email address error message
											  unset($_SESSION['invalidemail']);
											 }
											 ?>
		</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Email address</td>
      <td>&nbsp;</td>
      <td><input name="emailaddress" type="text" value="<?php
		  
		  //Displaying email address if set as session variable
		  if(isset($_SESSION['email_address'])){echo $_SESSION['email_address'];}?>" size="24" maxlength="32"></td>
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
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php
		  
		  //Displaying error message if city field is blank
		  if(isset($_SESSION['cityerror'])){echo $_SESSION['cityerror'];
										   
										   //Unsetting blank city error message
										   unset($_SESSION['cityerror']);} ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>City</td>
      <td>&nbsp;</td>
      <td><input name="city" type="text" size="24" maxlength="32" value="<?php
		  
		  //Displaying city if set as session variable
		  if(isset($_SESSION['city'])){echo $_SESSION['city'];}?>"></td>
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
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php
		  
											
			 //Displaying error message if state field is not changed
		  if(isset($_SESSION['stateselect'])){echo $_SESSION['stateselect']; unset($_SESSION['stateselect']);}
		  ?>
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>State</td>
      <td>&nbsp;</td>
      <td><select name="state">
        <option value="blank">Select your state:</option>
        <option value="AL">Alabama</option>
        <option value="AK">Alaska</option>
        <option value="AS">American Samoa</option>
        <option value="AZ">Arizona</option>
        <option value="AR">Arkansas</option>
        <option value="CA">California</option>
        <option value="CO">Colorado</option>
        <option value="CT">Conneticut</option>
        <option value="DE">Delaware</option>
        <option value="DC">District of Columbia</option>
        <option value="FL">Florida</option>
        <option value="GA">Georgia</option>
        <option value="GU">Guam</option>
        <option value="HI">Hawaii</option>
        <option value="ID">Idaho</option>
        <option value="IL">Illinois</option>
        <option value="IN">Indiana</option>
        <option value="IA">Iowa</option>
        <option value="KA">Kansas</option>
        <option value="KY">Kentucky</option>
        <option value="LA">Louisiana</option>
        <option value="ME">Maine</option>
        <option value="MD">Maryland</option>
        <option value="MA">Massachusetts</option>
        <option value="MI">Michigan</option>
        <option value="MN">Minnesota</option>
        <option value="MS">Mississippi</option>
        <option value="MO">Missouri</option>
        <option value="MT">Montana</option>
        <option value="NE">Nebraska</option>
        <option value="NV">Nevada</option>
        <option value="NH">New Hampshire</option>
        <option value="NJ">New Jersey</option>
        <option value="NM">New Mexico</option>
        <option value="NY">New York</option>
        <option value="NC">North Carolina</option>
        <option value="ND">North Dakota</option>
        <option value="MP">Northern Mariana Islands</option>
        <option value="OH">Ohio</option>
        <option value="OK">Oklahoma</option>
        <option value="OR">Oregon</option>
        <option value="PA">Pennsylvania</option>
        <option value="PR">Puerto Rico</option>
        <option value="RI">Rhode Island</option>
        <option value="SC">South Carolina</option>
        <option value="SD">South Dakota</option>
        <option value="TN">Tennessee</option>
        <option value="TX">Texas</option>
        <option value="UT">Utah</option>
        <option value="VT">Vermont</option>
        <option value="VA">Virginia</option>
        <option value="VI">Virgin Islands</option>
        <option value="WA">Washington</option>
        <option value="WV">West Virginia</option>
        <option value="WI">Wisconsin</option>
        <option value="WY">Wyoming</option>
      </select></td>
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
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php
		  
		  //Displaying error message if zip code field is blank
		  if(isset($_SESSION['ziperror'])){echo $_SESSION['ziperror'];
										  
										  //Unsetting blank zip code error message
										  unset($_SESSION['ziperror']);} ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Zip</td>
      <td>&nbsp;</td>
      <td><input name="zip" type="text" value="<?php
		  
		  //Displaying zip code if set as session variable
		  if(isset($_SESSION['zip'])){echo $_SESSION['zip'];}?>" size="5" maxlength="5"></td>
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
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php
								
		  //Displaying error message if phone number field is blank
		if(isset($_SESSION['mobilenumbererror'])){echo $_SESSION['mobilenumbererror'];
										   //Unsetting blank phone number error message
											unset($_SESSION['mobilenumbererror']);
										   }			
		  
		  //Displaying error message if phone number is associated with another member
		  if(isset($_SESSION['numberinuse'])){echo $_SESSION['numberinuse'];
										   //Unsetting phone number in use error message
											unset($_SESSION['numberinuse']);
										   }
											?>
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Phone Number</td>
      <td>&nbsp;</td>
      <td><input name="mobilenumber" type="tel" value="<?php
		  //Displaying phone number if set as session variable
		  if(isset($_SESSION['mobilenumber'])){echo $_SESSION['mobilenumber'];} ?>" size="10" maxlength="10"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(<small>Numbers only</small>)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5"><img src="../images/detail.png">&nbsp;<small>Your phone number is masked and never directly given to a locator.</small></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php
		  //Displaying error message if password isn't long enough
		  if(isset($_SESSION['passwordlength'])){echo $_SESSION['passwordlength'];
												//Unsetting password length error message
												unset($_SESSION['passwordlength']);}
		  //Displaying error message if password field is empty
		  if(isset($_SESSION['passwordoneerror'])){echo $_SESSION['passwordoneerror'];
												  //Unsetting empty password error message
												  unset($_SESSION['passwordoneerror']);}
		  //Displaying error message if confirmation password field is empty
		   if(isset($_SESSION['passwordtwo'])){echo $_SESSION['passwordtwo'];
											  //Unsetting empty confirmation password error message
											  unset($_SESSION['passwordtwo']);}
		  
		  			//Displaying error message if password and confirmation password fields don't match
		  		   if(isset($_SESSION['passwordmatch'])){echo $_SESSION['passwordmatch'];
														//Unsetting error message for unmatched passwords
														unset($_SESSION['passwordmatch']);}

		  ?>
		
		
		
		</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Password</td>
      <td>&nbsp;</td>
      <td colspan="2"><input name="password" type="password" size="24" maxlength="18" value="<?php
		  
		  //Displaying password if set as session variable
		  if(isset($_SESSION['sp'])){echo $_SESSION['sp'];}?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Confirm password</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="password"  name="confirmpassword" size="24" maxlength="18" value="<?php if(isset($_SESSION['sp'])){echo $_SESSION['sp'];}?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="hidden" name="formref" value="reg"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><input type="checkbox" name="checkbox" onClick="submit.disabled=false"><small>I agree to the <a href="#">Privacy Policy</a></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
		<td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">
		
		<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
		<td width="54%"><a href="exit_reg.php"><input type="button" value="Exit without Saving"></a></td>
      <td width="9%">&nbsp;</td>
      <td width="2%">&nbsp;</td>
      <td width="18%"><input type="submit" id="submit"value="Next" disabled="disabled"></td>
    </tr>
  </tbody>
</table>
			
		
		</td>
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