<?php
namespace PrivateArea\Modules\Api;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use PrivateArea\Modules\Api\Plugin\SecurityPlugin;
use PrivateArea\Modules\Api\Plugin\HandleErrorPlugin;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'PrivateArea\Modules\Api\Controllers' => __DIR__ . '/controllers/',
            'PrivateArea\Modules\Api\Plugin' => __DIR__ . '/plugin/',
            'PrivateArea\Modules\Api\Lib' => __DIR__ . '/lib/',  
        ]);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Setting up the view component
         */
        $di->set('view', function() {
            return new View();
        }, true);
        
        //Registering a dispatcher
        $di->set(
            "dispatcher",
            function () {
                // Create an events manager
                $eventsManager = new EventsManager();
        
                // Listen for events produced in the dispatcher using the Security plugin
                $eventsManager->attach(
                    "dispatch:beforeExecuteRoute",
                    new SecurityPlugin()
                );
        
                // Handle exceptions and not-found exceptions using NotFoundPlugin
                $eventsManager->attach(
                    "dispatch:beforeException",
                    new HandleErrorPlugin()
                );
        
                $dispatcher = new Dispatcher();
                
                //Set default namespace
                $dispatcher->setDefaultNamespace("PrivateArea\Modules\Api\Controllers");

                // Assign the events manager to the dispatcher
                $dispatcher->setEventsManager($eventsManager);
        
                return $dispatcher;
            }
        );          
    }
}
