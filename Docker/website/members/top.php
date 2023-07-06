<?php
$name_sel = "SELECT firstname FROM users WHERE uid = ?";
$stmt = $conn->prepare($name_sel);
$stmt->bind_param('s', $_SESSION['uid']);
if($stmt->execute()){$result = $stmt->get_result();
					$array = $result->fetch_assoc();
					$first_name = $array['firstname'];
					}
$stmt->close();

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="5%">&nbsp;</td>
      <td width="5%">&nbsp;</td>
      <td width="18%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="26%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="10%">Hello, <?php echo $first_name; ?></td>
		<td width="5%"><a href="notifications.php">Notifications</a></td>
    </tr>
  </tbody>
</table>