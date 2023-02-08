<style type="text/css">
body,td,th {
	font-family: Arial;
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

<?php  

//Starting mysqli connection
require('../connection.php');

		//Starting user session
		session_start();


//Directing to login if not logged in
if($_SESSION['uid'] == ""){header("location: index.php");}

		 
		  //Retrieving list of pets / selecting name and identifier
		  $petlist = "SELECT name, image, pid FROM pets WHERE uid = ?";
		  $stmt = $conn->prepare($petlist);
		  $stmt->bind_param('s', $_SESSION['uid']);
		  if($stmt->execute()){$result = $stmt->get_result();
							   
							   //Counting pet amount for user
							  $petcount = $result->num_rows;
							  
							   //If there are no pets...
							   if($petcount == 0){
								   //Displaying message if user has no pets added
								   echo "<center>You have no pets to display.</center>";}else{

							  //Loop printing table with details of pet
					while($data = $result->fetch_assoc()){
									//If pet has no name blank name is listed as incomplete draft
								  if($data['name'] == ""){$data['name'] = "Incomplete draft";}
		
		  echo "
		  
	<table width='300' border='0' cellspacing='0' cellpadding='0'>
  <tbody>
    <tr>
      <td width='102' rowspan='3'><img src='../$data[image]' width='100' height='100' border='1'></td>
      <td width='15'>&nbsp;</td>
      <td width='400'>$data[name]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><a href='manage_pet.php?petid=$data[pid]' base target='_parent'>Manage</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<tr>
      <td height='19'>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
		  ";}
							  }
}
		  ?>