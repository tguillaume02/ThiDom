<?php
include_once('../../Security.php');

header('Content-Type: application/json');

$WeatherStatus = curl_init();
curl_setopt_array($WeatherStatus, array(
  CURLOPT_RETURNTRANSFER  => 1,
  CURLOPT_URL           => 'http://weather.yahooapis.com/forecastrss?w=613814',
  CURLOPT_HTTPHEADER    => array('Content-type: text/xml')
));


$status = curl_exec($WeatherStatus);
curl_close($WeatherStatus); 
$dom = new DomDocument();
$dom->loadXML($status); 

$cible = $dom->getElementsByTagName("yweather:astronomy")->item(0);
$sunrise = $cible->getAttribute("sunrise");
$sunset = $sunset->getAttribute("sunset");


$cible = $dom->getElementsByTagName("yweather:condition")->item(0);
$condition = $cible->getAttribute("text");


echo $sunrise;
echo $sunset;
echo $condition;

?>