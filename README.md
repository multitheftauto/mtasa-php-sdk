# MTA:SA PHP SDK [![Build Status](https://dev.azure.com/multitheftauto/mtasa-php-sdk/_apis/build/status/multitheftauto.mtasa-php-sdk?branchName=master)](https://dev.azure.com/multitheftauto/mtasa-php-sdk/_build/latest?definitionId=1&branchName=master)
You can access the MTA Web Interface from almost any programming language that can request web pages. PHP can do this very easily.

This SDK provides one function call that will allow you to call any exported script functions on any server that you have access to.

See the [official wiki page](https://wiki.multitheftauto.com/wiki/PHP_SDK) for further information.

## Installation

### Prerequisites

This SDK require PHP 7.1 or greater

### Setup

The only supported installation method is via [Composer](https://getcomposer.org). Run the following command to require this SDK in your project:

```
composer require multitheftauto/mtasa-php-sdk
```

### HTTPlug client abstraction

As this SDK use HTTPlug, you will have to require some libraries for get it working. See at [HTTPlug for library users](http://docs.php-http.org/en/latest/httplug/users.html). 
## A simple example

You have multiple combinations for calling a mta server exported functions. Three ways are shown in example:
```php
<?php

require_once('vendor/autoload.php');

use MultiTheftAuto\Sdk\Mta;
use MultiTheftAuto\Sdk\Model\Server;
use MultiTheftAuto\Sdk\Model\Authentication;

$server = new Server('127.0.0.1', 22005);
$auth = new Authentication('myUser', 'myPassword');
$mta = new Mta($server, $auth);

$response = $mta->getResource('someResource')->call('callableFunction', $arg1, $arg2, $arg3, ...);
//or
$response = $mta->getResource('someResource')->callableFunction($arg1, $arg2, $arg3, ...);
//or also
$response = $mta->someResource->callableFunction($arg1, $arg2, $arg3, ...);

var_dump($response);
```
