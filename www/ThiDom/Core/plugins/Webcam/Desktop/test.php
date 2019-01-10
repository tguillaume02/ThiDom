<?php
/*
header("Content-Type: image/jpeg");

$ch = curl_init();


curl_setopt($ch, CURLOPT_URL, "http://192.168.1.14:8080/photo.jpg");

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_ALL);

curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$su = curl_exec($ch);

if ( !$success ) print "<br><B>Error!!</b><br>";

$output = curl_exec($ch);

$info = curl_getinfo($ch);

curl_exec($ch);

curl_close($ch);
echo $output;
echo $info;
*/

header("Content-Type: image/jpg");

//$url="http://www.ttelectronics.com/sites/default/files/inline-images/OPL530.PNG";
$urls = "http://192.168.1.79:8080/photo.jpg";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
$res = curl_exec($ch);
$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
curl_close($ch) ;
echo $rescode;

        ?>