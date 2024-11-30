<?php

declare(strict_types=1);

namespace SceraApi\PhpClient;

use RuntimeException;

final class ConvertException extends RuntimeException {

    public static function jsonDecodeFail(string $json): ConvertException {
        return new ConvertException("Could not parse JSON: '$json'");
    }

}