<?php 

echo 'running';
sleep(rand(1, 10));

$f = fopen(__DIR__ . '/' . $_ENV['TARGETFILE'], 'w');
fwrite($f, 'testing');
fclose($f);
