<?php

include __DIR__."/../vendor/autoload.php";

$server = new Registry\Client\Server\Consul("192.168.0.166", 8900);
$request = new Registry\Client\Fegin($server);

$result = $request->request("AYSaaS-mtao-eagle-user", "/getuser");

var_dump($result);
