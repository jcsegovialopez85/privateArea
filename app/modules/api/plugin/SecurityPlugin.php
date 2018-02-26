<?php
namespace PrivateArea\Modules\Api\Plugin;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
use PrivateArea\Modules\Api\Lib\RequestAuthorization;
use PrivateArea\Modules\Api\Lib\UserAuthorization;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{

		$requestAuthorization = new RequestAuthorization($this->request);
		$token = $requestAuthorization->getToken();
		if(!$token){
			$this->response->setStatusCode(401, "Unauthorized");
            return false;
		}
			
		$credentials = $requestAuthorization->getCredentials($token);
		$name = $this->filter->sanitize($credentials[0], "alphanum");
		$password = $this->filter->sanitize($credentials[1], "string");

		$user = $this->userData->findByNameAndPassword($name, $password);
		if(!$user){
			$this->response->setStatusCode(401, "Unauthorized");
            return false;
		}

		$userAuthorization = new UserAuthorization($this->request, $user);
		if(!$userAuthorization->isAuthorized()){
			$this->response->setStatusCode(401, "Unauthorized");
            return false;
		}
	
	}
}