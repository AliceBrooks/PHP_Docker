<?php

declare(strict_types=1);

namespace PHP_Docker\Tests;

use PHPUnit\Framework\TestCase;
use PHP_Docker\Container;
use PHP_Docker\Response;

final class ContainerTest extends TestCase
{
    public function testCreate(): void
    {
        $container = new Container('nginx');
        $response = $container->create();
        
        $this->assertInstanceOf(
            Response::class,
            $response,
            'Response object not returned'
        );

        $this->assertNotEmpty(
            $response->getId(),
            'Failed to create container: ' . $response->getMessage()
        );
    }
}
