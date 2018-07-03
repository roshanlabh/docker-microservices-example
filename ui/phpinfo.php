<?php
error_reporting(1);

echo "<br />URL: ".$url = 'http://users-php:8082/api/users';
$curl_log = fopen("curl.txt", 'rw'); // open file for READ and write
echo "<br />curl_log: ".$curl_log;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_VERBOSE, true);
// curl_setopt($ch, CURLOPT_STDERR, $curl_log);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch);

$info = curl_getinfo($ch);
echo "<pre>CURL info: "; print_r($info); echo "</pre>";

curl_close($ch);
$arr = json_decode($output, true);
echo "<pre>CURL output: "; print_r($output); echo "</pre>";
echo "<pre>Output Array: "; print_r($arr); echo "</pre>";

phpinfo();
?>
