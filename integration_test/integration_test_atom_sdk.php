<?php

require_once '../vendor/autoload.php';

use IronSourceAtom\Tracker;

foreach($argv as $value) {
  echo "$value\n";
}

function GenerateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $characters_length = strlen($characters);

    $random_str = '';
    for ($i = 0; $i < $length; $i++) {
        $random_str .= $characters[rand(0, $characters_length - 1)];
    }
    return $random_str;
}

function GenerateData($type, $is_autoincrement = false, $prev_value = NULL) {
	switch($type) {
		case "int":
			if ($is_autoincrement) {
				return $prev_value + 1;
			} else {
				return rand(1, 10000);
			}
		case "str":
			return GenerateRandomString();
		case "bool":
			return rand(0, 1) == 1;
	}
}

$stream = $argv[1];
$auth = $argv[2];
$event_count = $argv[3];
$bulk_size = $argv[4];
$bulk_size_byte = $argv[5];

$send_data_types = $argv[6];
$data_key_increment = $argv[7];

$flush_interval = $argv[8]; // in milliseconds

$data_types = json_decode($send_data_types, true);

$tracker = new Tracker();

$tracker->setAuthKey($auth);
$tracker->setBulkSize((int)$bulk_size);
$tracker->setBulkSizeByte((int)$bulk_size_byte);
$tracker->setFlushInterval((int)$flush_interval);

$tracker->setDebug(true);

print "Event send count: $event_count\n";

$prev_data = [];
$prev_data[$data_key_increment] = 0;

$event_count_int = (int)$event_count;

for ($index = 0; $index < $event_count_int; $index++) {
	$data = [];

	foreach ($data_types as $key => $value) {
		$is_inc = ($data_key_increment == $key);
		$data_value = NULL;
		if ($is_inc) {
			$prev_data[$data_key_increment] = GenerateData($value, $is_inc, $prev_data[$data_key_increment]);
			$data_value = $prev_data[$data_key_increment];
		} else {
			$data_value = GenerateData($value);
		}

		$data[$key] = $data_value;
	}

	$tracker->track($stream, json_encode($data));
}

$tracker->flush();

