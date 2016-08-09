<?php
require_once '../vendor/autoload.php';
use IronSourceAtom\Tracker;
$tracker = new Tracker();
$tracker->setAuthKey('I40iwPPOsG3dfWX30labriCg9HqMfL');
$tracker->setDebug(true);
for ($i = 1; $i <= 10; $i++){
    $tracker->track("ibtest", "{name: iron".$i.", last_name: source}");
}

$tracker->flush();

?>