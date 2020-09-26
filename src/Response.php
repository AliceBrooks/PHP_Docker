<?php

declare(strict_types=1);

namespace PHP_Docker;

class Response
{
    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getId(): string
    {
        if (isset($this->response['Id'])) {
            return $this->response['Id'];
        }

        return '';
    }

    public static function create(array $response): Response
    {
        return new static($response);
    }

    public static function createString($response): Response
    {
        return new static($response);
    }
}
