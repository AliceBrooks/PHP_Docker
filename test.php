<?php

declare(strict_types=1);

require "vendor/autoload.php";

$container = new PHP_Docker\Container('php');
$container->addMount('/home/pi/projects/php-docker/');
$container->addVolume('/home/pi/projects/php-docker/');
$container->setEntryPoint('php /home/pi/projects/php-docker/echo.php');
$container->setAutoRemove(false);
$container->create();
$container->start();
$container->waitForCompletion();

$out = $container->getLogs();

echo PHP_EOL;
echo $out->response;
echo PHP_EOL;
// $container->stop();
// $container->delete();
