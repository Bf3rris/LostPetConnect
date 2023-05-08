<?php

$token = "MjFhNmE5ZDItNDExZi00M2NhLWFmZjctODYwOGU2NmY0MGU0OlBUNGU4ODMwYTRjNjhhMzUxZGY2MTkyNDY5MWIwYWMyNzYzMmRlZDI2N2VhODE5YmU3";
$url = "https://bf3rris.signalwire.com/api/relay/rest/phone_numbers/search?areacode=937";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Authorization: Basic ' . $token
));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

$next = curl_exec($curl);
$data = curl_getinfo($curl);
file_put_contents('1.txt', $next);
print_r($next);
curl_close($curl);

?>