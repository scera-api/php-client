<?php

declare(strict_types=1);

namespace SceraApi\PhpClient;

use Throwable;

final class Client implements ISO4217 {

    private string $key;
    private string $server     = 'https://scera-api.com/api';
    private bool   $verifyPeer = true;

    /**
     * Create a new instance of the Client.
     *
     * @param string $key api key from your subscription in https://scera-api.com/
     */
    public function __construct(string $key) {
        $this->key = $key;
    }

    /**
     * Change basic parameters of the client.
     *
     * @param bool $verifyPeer you can deactivate verify peer in curl calls.
     *                         Keep in mind is important to leave it activated.
     */
    public function config(bool $verifyPeer): void {
        $this->verifyPeer = $verifyPeer;
    }

    public function convert(string $from, string $to, float $convert = 1.0): Result {
        $this->assert($from, $to);
        $url  = $this->buildUrl($from, $to, $convert);
        $json = $this->callWithCurl($url) ?? $this->callWithFileGetContents($url);
        if ($json === null) {
            throw new ConvertException();
        }
        $array = json_decode($json, true);
        if (!is_array($array)
            || !isset($array['from'])
            || !isset($array['to'])
            || !isset($array['convert'])
            || !isset($array['result'])
            || !isset($array['rate'])
            || !isset($array['quota'])
            || !is_string($array['from'])
            || !is_string($array['to'])
            || strlen($array['from']) != 3
            || strlen($array['to']) != 3
            || !is_numeric($array['convert'])
            || !is_numeric($array['result'])
            || !is_numeric($array['rate'])
            || !is_numeric($array['quota'])
        ) {
            throw ConvertException::jsonDecodeFail($json);
        }

        $from    = $array['from'];
        $to      = $array['to'];
        $convert = floatval($array['convert']);
        $result  = floatval($array['result']);
        $rate    = floatval($array['rate']);
        $quota   = intval($array['quota']);

        return new Result($from, $to, $convert, $rate, $result, $quota);
    }

    private function assert(string $from, string $to): void {
        $errors     = [];
        $currencies = self::CURRENCIES;
        if (!in_array($from, $currencies, true)) {
            $errors[] = "Currency '$from' is not a valid ISO 4217 currency code.'";
        }
        if (!in_array($to, $currencies, true)) {
            $errors[] = "Currency '$from' is not a valid ISO 4217 currency code.'";
        }

        if (count($errors) === 0) {
            return;
        }

        $message = implode(PHP_EOL, $errors);
        throw new InvalidArgumentException($message);

    }

    private function buildUrl(string $from, string $to, float $convert): string {
        $params = [
            'from'    => $from,
            'to'      => $to,
            'convert' => $convert,
            'key'     => $this->key,
        ];
        $query  = http_build_query($params);
        return $this->server . "?$query";
    }

    private function callWithCurl(string $url): ?string {
        if (!extension_loaded('curl')) {
            return null;
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => $this->verifyPeer
        ]);
        try {
            $response = curl_exec($curl);
            if (curl_errno($curl) || !is_string($response)) {
                return null;
            }
            return $response;
        } catch (Throwable $exception) {
            return null;
        }
    }

    private function callWithFileGetContents(string $url): ?string {
        try {
            $response = @file_get_contents($url);
            return $response === false ? null : $response;
        } catch (Throwable $exception) {
            return null;
        }
    }

}