<?php

$router = $di->getRouter();

foreach ($application->getModule("web") as $module) {
    $key = "web";
    $namespace = "PrivateArea\Modules\Web\Module";
    $router->add('/'.$key.'/:params', [
        'namespace' => $namespace,
        'module' => $key,
        'controller' => 'index',
        'action' => 'index',
        'params' => 1
    ])->setName($key);
    $router->add('/'.$key.'/:controller/:params', [
        'namespace' => $namespace,
        'module' => $key,
        'controller' => 1,
        'action' => 'index',
        'params' => 2
    ]);
    $router->add('/'.$key.'/:controller/:action/:params', [
        'namespace' => $namespace,
        'module' => $key,
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]);
}

//API
$router->addGet("/api/user/{name}",
    array(
        "module"	=> "api",
        "namespace" =>  "PrivateArea\Modules\Api\Controllers",
        "controller" => "user",
        "action"	 => "get",
        "params"    => 1
    )
);

$router->addGet("/api/user/list",
    array(
        "module"	=> "api",
        "namespace" =>  "PrivateArea\Modules\Api\Controllers",
        "controller" => "user",
        "action"	 => "list"
    )
);

$router->addPost("/api/user/",
    array(
        "module"	=> "api",
        "namespace" =>  "PrivateArea\Modules\Api\Controllers",
        "controller" => "user",
        "action"	 => "create",
    )
);

$router->addPut("/api/user/{name}",
    array(
        "module"	=> "api",
        "namespace" =>  "PrivateArea\Modules\Api\Controllers",
        "controller" => "user",
        "action"	 => "update"       
    )
);

$router->addDelete("/api/user/{name}",
    array(
        "module"	=> "api",
        "namespace" =>  "PrivateArea\Modules\Api\Controllers",
        "controller" => "user",
        "action"	 => "delete"
    )
);
 