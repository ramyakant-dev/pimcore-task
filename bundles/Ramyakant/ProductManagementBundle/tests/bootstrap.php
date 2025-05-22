<?php

// Define PIMCORE_PROJECT_ROOT first
define('PIMCORE_PROJECT_ROOT', dirname(__DIR__, 4));
// Define PIMCORE_SYMFONY_CACHE_DIRECTORY
define('PIMCORE_SYMFONY_CACHE_DIRECTORY', PIMCORE_PROJECT_ROOT . '/var/cache');
// Define PIMCORE_LOG_DIRECTORY
define('PIMCORE_LOG_DIRECTORY', PIMCORE_PROJECT_ROOT . '/var/log');
// Define PIMCORE_CONFIGURATION_DIRECTORY
define('PIMCORE_CONFIGURATION_DIRECTORY', PIMCORE_PROJECT_ROOT . '/config');
// Define PIMCORE_CLASS_DIRECTORY
define('PIMCORE_CLASS_DIRECTORY', PIMCORE_PROJECT_ROOT . '/var/classes');

require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Kernel;

// Set the test environment
putenv('APP_ENV=test');

// Boot the Pimcore kernel
$kernel = new Kernel('test', true);
$kernel->boot();