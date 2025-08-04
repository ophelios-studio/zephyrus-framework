<?php

use Models\Core\Application;
use Zephyrus\Core\Kernel;

define('ROOT_DIR', realpath(__DIR__ . '/..'));
require ROOT_DIR . '/vendor/autoload.php';

$kernel = new Kernel(Application::class);
$response = $kernel->run();
$response->send();
