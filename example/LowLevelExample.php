<?php

require_once '../vendor/autoload.php';
use IronSourceAtom\Atom;

$atom = new Atom();
$atom->setAuthKey("");

$data1 = array("id" => 1, "message" => "message from putEvent");
$atom->putEvent("ibtest", json_encode($data1));

$data2 = array("id" => 2, "message" => "first message to putEvents");
$data3 = array("id" => 3, "message" => "second message to putEvents");
$data4 = array("id" => 4, "message" => "third message to putEvents");
$eventsArray = array($data2, $data3, $data4);

$atom->putEvents("ibtest", json_encode($eventsArray));