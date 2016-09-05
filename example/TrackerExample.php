<?php
require_once '../vendor/autoload.php';
use IronSourceAtom\Tracker;

$tracker = new Tracker();
$tracker->setAuthKey("");
$tracker->setDebug(true);
for ($i = 1; $i <= 10; $i++) {
    $data = array("id" => $i, "message" => "message " . $i . " from tracker");
    $tracker->track("ibtest", json_encode($data));
}
$tracker->flush();

?>