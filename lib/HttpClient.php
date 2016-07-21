<?php

/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 7/21/16
 * Time: 12:11 PM
 */

$url = 'http://track.atom-data.io';
$data = '{
    "table" : "sdkdev_sdkdev.public.atomtestkeyone",
    "data" : "{\"name\": \"iron\", \"last_name\": \"source\"}",
    "auth" : "I40iwPPOsG3dfWX30labriCg9HqMfL"}';

// use key 'http' even if you send the request to https://...
$options = array(
'http' => array(
'header'  => 'Content-Type: application/json',
'method'  => 'POST',
'content' => $data
)
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }

var_dump($result);