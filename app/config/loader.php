<?php

use Phalcon\Loader;

require __DIR__ . '/../../vendor/autoload.php';

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'PrivateArea\Models' => APP_PATH . '/common/models/',
    'PrivateArea'        => APP_PATH . '/common/library/',
    'PrivateArea\Repository'   => APP_PATH . '/common/repository/',
    'PrivateArea\Library'   => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'PrivateArea\Modules\Web\Module' => APP_PATH . '/modules/web/Module.php',
    'PrivateArea\Modules\Api\Module' => APP_PATH . '/modules/api/Module.php'
]);

$loader->registerFiles([
         BASE_PATH . '/vendor/autoload.php'
    ],true
);


$loader->register();
