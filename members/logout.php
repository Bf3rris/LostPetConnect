<?php

//Starting user session
session_start();

//Ending user session
session_destroy();



//Redirecting to index page
if(isset($_SESSION['uid'])){}else{header("location: index.php"); exit;}

?>