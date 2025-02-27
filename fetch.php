<?php
$ch = curl_init();

$headers = [
    'Authorization :Bearer $token',
    'Content-Type : application/json'
];
curl_setopt_array($ch, [
    CURLOPT_URL => "https://randomuser.me/api/",
    CURLOPT_RETURNTRANSFER => true,

]);

$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$data = json_decode($response, true);
curl_close($ch);

print_r($data);


CREATE TABLE table_name(  
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
    create_time DATETIME COMMENT 'Create Time',
    name VARCHAR(255) NOT NULL COMMENT 'Name',
    priority INT NOT NULL COMMENT 'Priority',
    is_completed BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Is Completed',
    INDEX(name)
);