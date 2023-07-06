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
$child_id = $_REQUEST['uid'];

	  //Selecting call log data from db
	  $call_log = "SELECT * FROM call_log WHERE uid = ?";
	  $stmt = $conn->prepare($call_log);
	  $stmt->bind_param('s', $child_id);
	  if($stmt->execute()){$result = $stmt->get_result();
						   
		  //Row count used to show message if data exists or not
		  $num_rows = $result->num_rows;
						   
						   
		  if($num_rows == 0){
			  echo $num_rows;
			  			//Message displayed if no call data is available
			  echo "<tr><td>There are no calls to display.</td></tr>";}else{
			  
			  //If call log data exists for users
		    
		  while($array = $result->fetch_assoc()){
			  
				
			    //Selecting pet details to use in call data
			  $pn = "SELECT name, pid FROM pets WHERE uid = ?";
			  $stmt2 = $conn->prepare($pn);
			  $stmt2->bind_param('s', $child_id);
			  if($stmt2->execute()){$result2 = $stmt2->get_result();
								  $array2 = $result2->fetch_assoc();
									
									//Var holding pet name
								   $pet_name = $array2['name'];
									
									//Var holding pet id
									$pid = $array2['pid'];
								  
								  }
			  $stmt2->close();			   
			  					
			  				//Var holding request date of call pin
			  				$request_date = $array['date'];
			  
			  				//Var holding date of request of call pin
						   $request_time = $array['request_time'];
			  
			  				//Var holding phone number of pet (number will only show if it has called using pin)
						   $from_number = $array['from_number'];
			  
			  //Call log identification
			  	$logid = $array['id'];
			  
			  //Var holding notes data per contact
			  $note_data = $array['notes'];
			  
			  //Setting flag for indicator to show if notes data exists
			  if(strlen($note_data) < 1){$flag = "Add Notes";}else{$flag = "View Notes";}
			  
			  
			  
			  
	  echo "
	  
    <tr>
      <td><strong>Received:</strong><br />$request_date @ $request_time (GMT)</td>
    </tr>
	<tr>
	<td><strong>Pet:</strong><br /><a href='manage_pet.php?petid=$pid' target='_parent'>$pet_name</a></td>
	</tr>
	  <tr>
	  <td><strong>From:</strong><br />$from_number</td>
	  </tr>
	   <tr>
	  <td><strong>Action:</strong><br /><a href='../comm/call_out.php?id=$logid' target='_parent'>Call</a> &middot; <a href='block.php?id=$logid' target='_parent'>Block</a> &middot; <a href='notes.php?id=$logid' target='_parent'>$flag</a> &middot; <a href='delete.php?id=$logid' target='_parent'>Delete</a></td>
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