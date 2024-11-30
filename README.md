# Simple Currency Exchange Rate Api - PHP Client

PHP client for [SCERA](https://scera-api.com/), a simple currency exchange rate API.

## Requirements
- [PHP 7.4, 8.0, 8.1, 8.2, 8.3 or 8.4](https://php.net).

## Installation
With [composer](https://getcomposer.org/download/):
```php
composer require scera-api/php-client:^1.0.0
```

## Usage
### 1. API key
Get your api key in [SCERA](https://scera-api.com/).

### 2. Consume
```php
<?php

use SceraApi\PhpClient\Client;

$client = new Client($token);
try {
    $result = $client->convert('USD', 'EUR', 100.0);
} catch (Throwable $t) {
    //Manage the error
}

$rate   = $result->getRate();       //Example: 0.94525
$result = $result->getResult();     //Example: 94.525
```
That's it!