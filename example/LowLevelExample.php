<?php

require_once '../vendor/autoload.php';
use IronSourceAtom\Atom;

$atom = new Atom("I40iwPPOsG3dfWX30labriCg9HqMfL");
$atom->putEvent("sdkdev_sdkdev.public.atomtestkeyone", "{name: iron, last_name: source}");
$atom->putEvents("sdkdev_sdkdev.public.atomtestkeyone", "[{name: iron, last_name: source}]");