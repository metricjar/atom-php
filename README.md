# ironSource.atom SDK for PHP
[![License][license-image]][license-url]
[![Build status][travis-image]][travis-url]
[![Coverage Status][coveralls-image]][coveralls-url]
[![Docs][docs-image]][docs-url]

atom-php is the official ironSource.atom SDK for the PHP programming language.

- [Signup](https://atom.ironsrc.com/#/signup)
- [Documentation][docs-url]
- [Installation](#installation)
- [Usage](#usage)
- [Change Log](#chagne-log)
- [Example](#example)

## Installation

Using [Composer](https://getcomposer.org/) is the recommended way to install the Atom SDK for PHP.

 1)  Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

 2)  Run the Composer command to install the latest stable version of the SDK:
    
```bash
php composer.phar require ironsourceatom/atom-php
```

 3)  Require Composer's autoloader:

```php
 <?php
    require 'vendor/autoload.php';
```

You can find out more on how to install Composer, configure auto loading and other best-practices  
for defining dependencies at the [Composer website](https://getcomposer.org/)

Also you can add the following into your composer.json
```json
"require": { "ironsourceatom/atom-php": ">=1.0" }
```

Then execute
```bash
$ php composer.phar update
$ php composer.phar install
```

## Usage

You may use SDK in two different ways:

1. High level Tracker - contains SQLite data base storage and tracks events based on certain parameters.
2. Low level - contains 2 methods: putEvent() and putEvents() to send 1 event or a batch respectively.

### Tracker usage

```php
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
```

The Tracker process:

You can use track() method in order to track the events to an Atom Stream.
The tracker accumulates events and flushes them when it meets one of the following conditions:
 
1. Flush Interval is reached (default: 10 seconds).
2. Bulk Length is reached (default: 4 events).
3. Maximum Bulk size is reached (default: 64kB).

The tracker stores events in a memory storage based on SQLite database.

### Low Level methods

```php
<?php
 
 require_once '../vendor/autoload.php';
 use IronSourceAtom\Atom;
 
 $atom = new Atom("");
 $atom->putEvent("ibtest", "{name: iron, last_name: source}");
 $atom->putEvents("ibtest", "[{name: iron, last_name: source}, {name: iron1, last_name: source1}]");

?>
```

## Change Log

### v1.1.0
- Added Tracker
- Added Composer support
- Added SQLite db support for tracker storage

### v1.0.0
- Basic features: putEvent & putEvents functionalities

## Example

You can use our [example][example-url] for sending data to Atom:


[example-url]: example
[license-image]: https://img.shields.io/badge/license-MIT-blue.svg
[license-url]: LICENSE
[travis-image]: https://travis-ci.org/ironSource/atom-php.svg?branch=master
[travis-url]: https://travis-ci.org/ironSource/atom-php
[coveralls-image]: https://coveralls.io/repos/github/ironSource/atom-php/badge.svg?branch=master
[coveralls-url]: https://coveralls.io/github/ironSource/atom-php/?branch=master
[docs-image]: https://img.shields.io/badge/docs-latest-blue.svg
[docs-url]: https://ironsource.github.io/atom-php/
