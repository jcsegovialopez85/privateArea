<?php
namespace PrivateArea\Modules\Web\Plugin;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
	protected $acl;
	protected $roleGuests;
	protected $rolesUsers;
	protected $allRoles;
	protected $privateResources;
	protected $publicResources;
	
	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{

		if (!isset($this->persistent->acl))
		{
			$this->acl = new AclList();
			$this->acl->setDefaultAction(Acl::DENY);

			$this->addPublicRole();
			$this->addPrivateRoles();
			$this->addPublicResources();
			$this->addPrivateResources();
			$this->grantAccessPublicArea();
			$this->grantAccessPrivateArea();
	
			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $this->acl;
		}
		return $this->persistent->acl;
	}

	private function addPublicRole()
	{
		// Register public role
		$this->roleGuests = new Role("Guests");
		$this->acl->addRole($this->roleGuests);
	}

	private function addPrivateRoles()
	{
		// Register private roles
		$rolesUser = ["ROLE_ADMIN", "ROLE_PAGE1", "ROLE_PAGE2", "ROLE_PAGE3"];
		foreach($rolesUser as $role)
		{
			$roleObj = new Role($role);
			$this->rolesUsers[] = $roleObj;
			$this->acl->addRole($roleObj);
		}
	}

	private function addPublicResources()
	{
		//Public area resources
		$this->publicResources = [
			'index'      => ['index','login','logout'],
			'errors'     => ['show401', 'show404', 'show500']
		];
		foreach ($this->publicResources as $resource => $actions)
		{
			$this->acl->addResource(new Resource($resource), $actions);
		}
	}

	private function addPrivateResources()
	{
		//Private area resources
		$this->privateResources = [
			'page1'   => ['index'],
			'page2'   => ['index'],
			'page3'   => ['index'],
		];
		
		foreach ($this->privateResources as $resource => $actions)
		{
			$this->acl->addResource(new Resource($resource), $actions);
		}
	}

	private function grantAccessPublicArea()
	{
		//Grant access to public areas to both users and guests
		foreach ($this->acl->getRoles() as $role)
		{
			foreach ($this->publicResources as $resource => $actions)
			{
				foreach ($actions as $action)
				{
					$this->acl->allow($role->getName(), $resource, $action);
				}
			}
		}
	}

	private function grantAccessPrivateArea()
	{
		 $pagesRoles = [
			'page1' => ["ROLE_ADMIN","ROLE_PAGE1"],      
			'page2' => ["ROLE_ADMIN","ROLE_PAGE2"],
			'page3' => ["ROLE_ADMIN","ROLE_PAGE3"],
		];

		//Grant access to private area to role Users
		foreach ($this->privateResources as $resource => $actions)
		{
			foreach ($actions as $action)
			{
				foreach($pagesRoles[$resource] as $role)
				{
					$this->acl->allow($role, $resource, $action);
				}
			}
		}
	}


	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$auth = $this->session->get('auth');
		$roles = $this->getActualRoles($auth);
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		$acl = $this->getAcl();

		if (!$acl->isResource($controller)) {
			$dispatcher->forward([
				'controller' => 'errors',
				'action'     => 'show404'
			]);
			return false;
		}

		$allowed = $this->roleHasAccess($acl, $roles, $controller, $action);

		if (!$allowed)
		{
			if(!$auth)
			{
				$this->registerPreviousPage($controller, $action);
				$dispatcher->forward([
					'controller' => 'index',
					'action'     => 'login'
				]);

			}else
			{
				$dispatcher->forward([
					'controller' => 'errors',
					'action'     => 'show401'
				]);
			}
	
			return false;
		}

		$this->checkInactivity($auth);
	}

	private function getActualRoles($auth)
	{
		if (!$auth)
		{
			return  array('Guests');
		} 
		
		$user = $this->userData->findByName($auth['name']);
		return $user->getRoles();
	}

	private function roleHasAccess($acl, $roles, $controller, $action)
	{
		$allowed = false;
		$i = 0;
		while($i<count($roles) && !$allowed)
		{
			$allowed = $acl->isAllowed($roles[$i], $controller, $action);
			$i++;
		}	
		return $allowed;
	}

	private function registerPreviousPage($controller, $action){
		$this->session->set("previousController", $controller); 
		$this->session->set("previousAction", $action);
	}

	private function checkInactivity($auth){
		$max_time = 300; //5 minutes in seconds

		if($auth)
		{
			if(!$this->session->has("lastUserInteraction"))
			{
				$this->session->set("lastUserInteraction", time());
			}else
			{
				if(time() - $this->session->get("lastUserInteraction") > $max_time)
				{
					$this->session->destroy();
					return $this->response->redirect("/index");
				}else
				{
					$this->session->set("lastUserInteraction", time());
				}
			}
		}
	}
}