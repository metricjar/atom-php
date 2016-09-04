# ironSource.atom SDK for PHP
[![License][license-image]][license-url]
[![Build status][travis-image]][travis-url]
[![Coverage Status][coveralls-image]][coveralls-url]
[![Docs][docs-image]][docs-url]

atom-php is the official ironSource.atom SDK for the PHP programming language.

- [Signup](https://atom.ironsrc.com/#/signup)
- [Documentation][docs-url]
- [Installation](#Installation)
- [Sending an event](#Using)

## Installation
Using Composer is the recommended way to install the Atom SDK for PHP.

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

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at getcomposer.org.

Also you can add the following into your composer.json
```json
"require": {
		"ironsourceatom/atom-php": ">=1.0"
	}
```
Then execute
```bash
$ php composer.phar update
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

?>
```

### Example

You can use our [example][example-url] for sending data to Atom:


[example-url]: https://github.com/ironSource/atom-php/tree/feature/isa-454/example
[license-image]: https://img.shields.io/badge/license-MIT-blue.svg
[license-url]: LICENSE.txt
[travis-image]: https://travis-ci.org/ironSource/atom-php.svg?branch=feature%2Fisa-454
[travis-url]: https://travis-ci.org/ironSource/atom-php
[coveralls-image]: https://coveralls.io/repos/github/ironSource/atom-php/badge.svg?branch=feature%2Fisa-454
[coveralls-url]: https://coveralls.io/github/ironSource/atom-php/?branch=feature%2Fisa-454
[docs-image]: https://img.shields.io/badge/docs-latest-blue.svg
[docs-url]: https://ironsource.github.io/atom-php/
