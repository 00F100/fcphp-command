# FcPhp Command

Package to manupulate commands of terminal into FcPhp

[![Build Status](https://travis-ci.org/00F100/fcphp-command.svg?branch=master)](https://travis-ci.org/00F100/fcphp-command) [![codecov](https://codecov.io/gh/00F100/fcphp-command/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-command)

[![PHP Version](https://img.shields.io/packagist/php-v/00f100/fcphp-command.svg)](https://packagist.org/packages/00F100/fcphp-command) [![Packagist Version](https://img.shields.io/packagist/v/00f100/fcphp-command.svg)](https://packagist.org/packages/00F100/fcphp-command) [![Total Downloads](https://poser.pugx.org/00F100/fcphp-command/downloads)](https://packagist.org/packages/00F100/fcphp-command)

## How to install

Composer:
```sh
$ composer require 00f100/fcphp-command
```

or add in composer.json
```json
{
    "require": {
        "00f100/fcphp-command": "*"
    }
}
```

## How to use

This package use [FcPhp Security Console](https://github.com/00F100/fcphp-sconsole) to manipulate permissions and [FcPhp Cache](https://github.com/00F100/fcphp-cache) to save commands cache for better performance

```php

use FcPhp\SConsole\SCEntity;
use FcPhp\Command\Interfaces\ICEntity;
use FcPhp\Command\Facades\CommandFacade;


// Instance of SCEntity provider from FcPhp Security Console
// or ...
$entity = new SCEntity();

// Custom commands...
$commands = [];

// Composer dir to autoload find "commands.php" into packages and cache into FcPhp Cache
$vendorPathAutoload = 'vendor/*/*/config';

$instance = CommandFacade::getInstance($entity, $commands, $vendorPathAutoload);

// Args from console request
// Example: php index.php package datasource connect -h localhost -u user -p password
$args = [
    'package',
    'datasource',
    'connect',
    '-h',
    'localhost',
    '-u',
    'user',
    '-p',
    'password'
];

// Return instance FcPhp\Command\Interfaces\ICEntity
$match = $this->instance->match($args);

if($match instanceof ICEntity) {
    // Print status code
    echo $match->getStatusCode();

    // Print action
    echo $match->getAction();

    // ...
}
```

### [FcPhp\Command\Interfaces\ICEntity](https://github.com/00F100/fcphp-command/tree/master/src/Interfaces/ICEntity.php)

```php
<?php

namespace FcPhp\Command\Interfaces
{
    interface ICEntity
    {
        /**
         * Method to contruct instance
         *
         * @param array $params Params to entity
         * @return void
         */
        public function __construct(array $params = []);

        /**
         * Method to get command
         *
         * @return string|null
         */
        public function getCommand();

        /**
         * Method to get action
         *
         * @return string|null
         */
        public function getAction();

        /**
         * Method to get rule
         *
         * @return string|null
         */
        public function getRule();

        /**
         * Method to get params to controller
         *
         * @return array
         */
        public function getParams() :array;

        /**
         * Method to get status code
         *
         * @return int
         */
        public function getStatusCode() :int;

        /**
         * Method to get status message
         *
         * @return string|null
         */
        public function getStatusMessage();
    }
}
```