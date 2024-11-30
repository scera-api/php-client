<?php

declare(strict_types=1);

use SceraApi\PhpClient\Client;

include __DIR__ . '/vendor/autoload.php';

$token  = $argv[1] ?? '';
$client = new Client($token);
try {
    $result = $client->convert('USD', 'EUR', 500.0);
} catch (Throwable $t) {
    echo "Fail: " . $t->getMessage() . PHP_EOL;
    exit(1);
}
if ($result->getFrom() === 'USD'
    && $result->getTo() === 'EUR'
    && $result->getConvert() === 500.0
    && is_int($result->getQuota())
    && is_float($result->getRate())
    && is_float($result->getResult())
) {
    echo "Pass" . PHP_EOL;
    echo print_r($result, true) . PHP_EOL;
} else {
    echo "Fail" . PHP_EOL;
}