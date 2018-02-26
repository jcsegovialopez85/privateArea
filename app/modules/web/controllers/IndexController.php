<?php

namespace PrivateArea\Modules\Web\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction(){}

	public function loginAction()
	{
		if ($this->request->isPost()) 
		{
			if ($this->security->checkToken()) 
			{
				$login	=  $this->filter->sanitize($this->request->getPost("login"), "alphanum");
				$password = $this->filter->sanitize($this->request->getPost("password"), "string");

				$user = $this->userData->findByNameAndPassword($login, $password);
				if($user!==false){
					$this->registerSession($user->getName());
					$this->redirectNextPage();
				} else 
				{
					// To protect against timing attacks. Regardless of whether a user exists or not, the script will take roughly the same amount as it will always be computing a hash.
					$this->security->hash(rand());
					$this->flashSession->error("User or password not valid");  
					return $this->response->redirect("/index/login");
				}
			}else
			{
				//Fail token
				return $this->response->redirect("/index");
			}
		}
	}

	public function logoutAction()
	{
		// Destroy the whole session
		$this->session->destroy();
		return $this->response->redirect("/index");
	}

    private function registerSession($user)
    {
        $this->session->set(
            "auth",
            [
                "name" => $user,
            ]
        );
	}

	private function redirectNextPage(){
		$previousController = $this->session->get("previousController");
		$previousAction = $this->session->get("previousAction");
		if($previousController!=null && $previousAction!=null)
		{
			return $this->response->redirect(sprintf('/%s/%s', $previousController, $previousAction));
		}

		return $this->response->redirect("/index");
	}
}

