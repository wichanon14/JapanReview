<?php 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://jisho.org/api/v1/search/words?keyword=Thai");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch); 

echo curl_exec($ch);

curl_close($ch); 

?>