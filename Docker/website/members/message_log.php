<style type="text/css">
body,td,th {
	font-family: Arial;
}
</style>
<?php

//Start mysqli connection
require('../connection.php');

//Start user session
//session_start();

echo"
<table width='280' border='0' cellspacing='0' cellpadding='0' align='left'>
  <tbody>
    <tr>
      <td>&nbsp;</td>
    </tr>
	";


//Var set to use users uid to pass to child page
$child_id = strip_tags($_REQUEST['uid']);


		//Selecting log id and pet id from message log to display data
	  $message_log = "SELECT id, from_number, petid, time, date, notes FROM message_log WHERE uid = ?";
	  $stmt = $conn->prepare($message_log);
	  $stmt->bind_param('s', $child_id);
	  if($stmt->execute()){
		  

	  $result = $stmt->get_result();
		  
		  //Used to determine message to display for data/no data in logs
		  $num_rows = $result->num_rows;
		  if($num_rows == 0){
			  
			  
			  		//Message displayed if no data is returned
			  echo "<tr><td>There are no messages to display.</td></tr>";}else{
		  while($array = $result->fetch_assoc()){
			  
			  
			  //Retrieve pets name and pet id
			  $name_select = "SELECT name, pid FROM pets WHERE uid = ?";
			  $stmt2 = $conn->prepare($name_select);
			  $stmt2->bind_param('s', $child_id);
			  if($stmt2->execute()){$result2 = $stmt2->get_result();
								  $array2 = $result2->fetch_assoc();
									
									//Pet name
								   $pet_name = $array2['name'];
									
									//Pet id
								  $pid = $array2['pid'];
								  }
			  $stmt2->close();
						   
			  
			  				//Time message was received
						   $time = $array['time'];
			  
			  				//Date message was received
			  				$date = $array['date'];
			  
			  				//Locators phone number
						   $from_number = $array['from_number'];
			  
			  
			  //Var holding notes data about contact
			  $note_data = $array['notes'];
			  
			  
			  //Message log id
			  $mid = $array['id'];
			  
			  
			  //Setting flag for indicator to show if notes data exists
			  if(strlen($note_data) < 1){$flag = "Add Notes";}else{$flag = "View Notes";}
			  
	  echo "
	  
    <tr>
      <td><strong>Received:</strong><br />$date @ $time (GMT)</td>
    </tr>
	<tr>
	<td><strong>Pet:</strong><br /><a href='manage_pet.php?petid=$pid' target='_parent'>$pet_name</a></td>
	</tr>
	  <tr>
	  <td><strong>From:</strong><br />$from_number</td>
	  </tr>
	   <tr>
	  <td><strong>Action:</strong><br /><a href='../comm/call_out.php?mid=$mid' target='_parent'>Call</a> &middot; <a href='block.php?mid=$mid' target='_parent'>Block</a> &middot; <a href='notes.php?mid=$mid' target='_parent'>$flag</a> &middot; <a href='delete.php?mid=$mid' target='_parent'>Delete</a></td>
	  </tr>
	  <tr>
	  <td align='left'><hr width='100%' color='#000000'></td>
	  </tr>
	  ";}
		}$stmt->close();
		  echo"
  </tbody>
</table>";
	  }
?>