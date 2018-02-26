<?php
namespace PrivateArea\Modules\Web;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use PrivateArea\Modules\Web\Plugin\SecurityPlugin;
use PrivateArea\Modules\Web\Plugin\HandleErrorPlugin;

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
            'PrivateArea\Modules\Web\Controllers' => __DIR__ . '/controllers/',
            'PrivateArea\Modules\Web\Plugin' => __DIR__ . '/plugin/',
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
        $di->set('view', function () {
            $view = new View();
            $view->setDI($this);
            $view->setViewsDir(__DIR__ . '/views/');

            $view->registerEngines([
                '.volt'  => 'voltShared',
            ]);

            return $view;
        });

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
        
                // Handle exceptions and not-found exceptions using ErrorPlugin
                $eventsManager->attach(
                    "dispatch:beforeException",
                    new HandleErrorPlugin()
                );
        
                $dispatcher = new Dispatcher();
                
                //Set default namespace to backend module
                $dispatcher->setDefaultNamespace("PrivateArea\Modules\Web\Controllers");
                
                // Assign the events manager to the dispatcher
                $dispatcher->setEventsManager($eventsManager);
        
                return $dispatcher;
            }
        );

        /**
         * Starts the session the first time some component requests the session service
         */

		$di->setShared(
			"session",
			function () {
				// All variables created will prefixed with 'session-web'
				$session = new SessionAdapter(
					[
						'uniqueId' => 'session-web',
					]
				);

				$session->start();

				return $session;
			}
		);

        // Set up the flash session service
        $di->set(
            "flashSession",
            function () {
                $flashSession = new FlashSession(
                    [
                        "error"   => "alert alert-danger",
                        "success" => "alert alert-success",
                        "notice"  => "alert alert-info",
                        "warning" => "alert alert-warning",
                    ]
                );

                return $flashSession;
            }
        );
    }
}
