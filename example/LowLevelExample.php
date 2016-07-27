<?php

require_once '../vendor/autoload.php';
use IronSourceAtom\Atom;

$atom = new Atom("");
$atom->putEvent("ibtest", "{name: iron, last_name: source}");
$eventsArray = array("{name: iron, last_name: source}", "{name: iron2, last_name: source2}");

$atom->putEvents("sdkdev_sdkdev.public.atomtestkeyone", json_encode($eventsArray));