<?php

declare(strict_types=1);

namespace PHP_Docker;

class Request
{
    private $ch;
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_DELETE = 'DELETE';

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_UNIX_SOCKET_PATH, '/var/run/docker.sock');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_BUFFERSIZE, 256);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 1000000);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public function send($path, string $data = '', $method = 'GET'): Response
    {
        curl_setopt($this->ch, CURLOPT_URL, "http://unixsocket/$path");

        if ($method === static::METHOD_POST) {
            curl_setopt($this->ch, CURLOPT_POST, true);
        }

        if ($method === static::METHOD_DELETE) {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, static::METHOD_DELETE);
        }

        if ($data) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($this->ch);
        $responseCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        curl_close($this->ch);

        if ($this->isJson($response)) {
            return Response::create(json_decode($response, true), $responseCode);
        } elseif ($response) {
            return Response::create($response, $responseCode);
        }

        return Response::create(['No response']);
    }

    public function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
