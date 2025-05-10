<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = _DIR_.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require _DIR_.'/../vendor/autoload.php';

$app = require_once _DIR_.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);