<?php

declare(strict_types=1);

namespace PHP_Docker;

class Response
{
    public $response;
    public int $responseCode;

    public function __construct($response, $responseCode)
    {
        $this->response = $response;
        $this->responseCode = (int)$responseCode;
    }

    public function getId(): string
    {
        if (isset($this->response['Id'])) {
            return $this->response['Id'];
        }

        return '';
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function getMessage(): string
    {
        return $this->response['message'];
    }

    public static function create($response, $responseCode = 200): Response
    {
        return new static($response, $responseCode);
    }
}
