<?php

require('vendor/autoload.php');

use \SignalWire\LaML\MessageResponse;

$response = new MessageResponse;

//$all = $_REQUEST;
//file_put_contents("file.txt", $all);
//$count = $_REQUEST['NumMedia'];
$media = $_REQUEST['MediaUrl0'];

//file_put_contents("count.txt", $count);
	
file_put_contents("data.txt", $media);


header("content-type: text/xml");
echo $response->asXML();



?>