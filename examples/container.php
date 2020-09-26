<?php

use \PHP_Docker\Container;

// Create container from image
$container = new Container('php_72');

// Add mount, secondary path infered
$container->addMount('/var/www/vhosts');

// Add mount, secondary path specificed
$container->addMount('/var/www/vhosts', '/var/www/vhosts');

// Set more specific options from array
$container->setOptions(
    [
        'name' => 'php_7-2',
        'ports' => '9000:80',
    ]
);

// create and run
$container->create();

// Generate the run command
// $container->outCommand('run-command.txt');
