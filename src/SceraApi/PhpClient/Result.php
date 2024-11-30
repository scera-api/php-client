<?php

declare(strict_types=1);

namespace SceraApi\PhpClient;

final class Result {

    private string $from;
    private string $to;
    private float  $convert;
    private float  $rate;
    private float  $result;
    private int    $quota;

    public function __construct(string $from, string $to, float $convert, float $rate, float $result, int $quota) {
        $this->from    = $from;
        $this->to      = $to;
        $this->convert = $convert;
        $this->rate    = $rate;
        $this->result  = $result;
        $this->quota   = $quota;
    }

    public function getFrom(): string {
        return $this->from;
    }

    public function getTo(): string {
        return $this->to;
    }

    public function getConvert(): float {
        return $this->convert;
    }

    public function getRate(): float {
        return $this->rate;
    }

    public function getResult(): float {
        return $this->result;
    }

    public function getQuota(): int {
        return $this->quota;
    }

}