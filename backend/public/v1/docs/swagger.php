<?php

require __DIR__ . '/../../../../vendor/autoload.php';



error_reporting(0);
$openapi = \OpenApi\Generator::scan([__DIR__ . '/../../routes']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
?>