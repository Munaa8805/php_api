<?php
$ch = curl_init();
curl_setopt_array($ch, [CURLOPT_URL => "https://randomuser.me/api/", CURLOPT_RETURNTRANSFER => true]);

$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$data = json_decode($response, true);
curl_close($ch);

var_dump($data);