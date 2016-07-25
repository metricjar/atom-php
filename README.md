# ironSource.atom SDK for PHP
[![License][license-image]][license-url]
[![Build status][travis-image]][travis-url]
[![Coverage Status][coveralls-image]][coveralls-url]
[![Docs][docs-image]][docs-url]

Atom-Ruby is the official ironSource.atom SDK for the PHP programming language.

- [Signup](https://atom.ironsrc.com/#/signup)
- [Documentation][docs-url]
- [Installation](#Installation)
- [Sending an event](#Using)

## Installation
Using Composer is the recommended way to install the Atom SDK for PHP.

    Install Composer

    curl -sS https://getcomposer.org/installer | php

    Run the Composer command to install the latest stable version of the SDK:

    php composer.phar require ironsourceatom/atom-php

    Require Composer's autoloader:

    <?php
    require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at getcomposer.org.

Also you can add the following into your composer.json
```json
"require": {
		"ironsourceatom/atom-php": ">=1.0"
	}
```
Then execute
```bash
$ php composer.phar install
```
### Using low level API methods

```php
<?php
 
 require_once '../vendor/autoload.php';
 use IronSourceAtom\Atom;
 
 $atom = new Atom("");
 $atom->putEvent("ibtest", "{name: iron, last_name: source}");
 $atom->putEvents("ibtest", "[{name: iron, last_name: source}, {name: iron1, last_name: source1}]");

```

### Example

You can use our [example][example-url] for sending data to Atom:


[example-url]: https://github.com/ironSource/atom-ruby/tree/feature/ISA-359/example
[license-image]: https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square
[license-url]: LICENSE.txt
[travis-image]: https://travis-ci.org/ironSource/atom-ruby.svg?branch=master
[travis-url]: https://travis-ci.org/ironSource/atom-ruby
[coveralls-image]: https://coveralls.io/repos/github/ironSource/atom-ruby/badge.svg?branch=master
[coveralls-url]: https://coveralls.io/github/ironSource/atom-ruby?branch=master
[docs-image]: https://img.shields.io/badge/docs-latest-blue.svg
[docs-url]: https://ironsource.github.io/atom-ruby/
