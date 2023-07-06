<?php

//Starting user session
session_start();

//Ending user session
session_destroy();


//Redirecting to index page
header("location: index.php");
exit;

?>