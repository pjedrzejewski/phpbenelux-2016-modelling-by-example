<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

umask(0000);

$loader = require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../AppKernel.php';

Debug::enable();

$kernel = new AppKernel('dev', true);
$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
