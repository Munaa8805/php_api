<?php
$ch = curl_init();

$headers = [
    'Authorization :Bearer $token',
    'Content-Type : application/json'
];
curl_setopt_array($ch, [
    CURLOPT_URL => "https://randomuser.me/api/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_HEADER => true,
]);

$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$data = json_decode($response, true);
curl_close($ch);

var_dump($data);