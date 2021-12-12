<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

// ensure a fresh cache when debug mode is disabled
echo sprintf("Clearing cache... \n\r");
(new \Symfony\Component\Filesystem\Filesystem())->remove(__DIR__ . '/../var/cache/test');
echo sprintf("Cache clear ! \n\r");
