<?php
namespace PrivateArea\Modules\Api\Controllers;

use Phalcon\Mvc\Controller;
use PrivateArea\Models\User;
use PrivateArea\Modules\Api\Lib\RequestContent;
use PrivateArea\Modules\Api\Lib\ResponseHandler;

class UserController extends Controller
{
    protected $responseHandler;

    public function initialize()
    {
        $this->responseHandler = new ResponseHandler();
    }

    public function indexAction(){}

    public function listAction()
    {
        $users = $this->userData->findAll();
        $data = ['users' => [], 'total' => count($users)];

        foreach ($users as $user) 
        {
            $data['users'][] = [
                "name" => $user->getName(),
                "roles" => $user->getRoles(),
                "links" => ["rel" => "self", "href" => sprintf('/api/user/%s', $user->getName())],
            ];
        }

        return $this->responseHandler->negotiate($this->request, $data);
	}

    public function getAction($name)
    {
        $name = $this->filter->sanitize($name, "alphanum");
        $user = $this->userData->findByName($name);

        if (!$user) {
            return $this->response->setStatusCode(404, "Not Found");
        }
        $data = [
                "name" => $user->getName(),
                "roles" => $user->getRoles(),
                "links" => ['rel' => 'self', "href" => sprintf('/api/user/%s', $user->getName())],
        ];

        return $this->responseHandler->negotiate($this->request, $data);
    }

    
    public function createAction(){
        $requestContent = new RequestContent($this->request);

        if(!$requestContent->canDeserializeContent()){
            $this->response->setStatusCode(415, "Unsupported Media Type");
            return false;
        }

        $content = $requestContent->getContent();
        if(!isset($content->name) || !isset($content->roles) || (!isset($content->password))){
            return $this->response->setStatusCode(400, "BAD REQUEST");
        }

        $name = $this->filter->sanitize($content->name, "alphanum");
        $roles = $this->filter->sanitize($content->roles, "string");
        $password =  $this->filter->sanitize($content->password, "string");

        $user = new User($name, $roles);
        $this->userData->updateUser($user, $password);  

        $data = [
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
            'links' => ['rel' => 'self', "href" => sprintf('/api/user/%s', $user->getName())],
        ];

        return $this->responseHandler->negotiate($this->request, $data);

    }

    public function updateAction($name)
    {
        $requestContent = new RequestContent($this->request);

        if(!$requestContent->canDeserializeContent()){
            $this->response->setStatusCode(415, "Unsupported Media Type");
            return false;
        }

        $content = $requestContent->getContent();
        if(!isset($name) || !isset($content->roles) || (!isset($content->password))){
            return $this->response->setStatusCode(400, "BAD REQUEST");
        }

        $name = $this->filter->sanitize($name, "alphanum");
        $roles = $this->filter->sanitize($content->roles, "string");
        $password =  $this->filter->sanitize($content->password, "string");

        $user = new User($name, $roles);
        $this->userData->updateUser($user, $password);  

        $data = [
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
            'links' => ['rel' => 'self', "href" => sprintf('/api/user/%s', $user->getName())],
        ];

        return  $this->responseHandler->negotiate($this->request, $data);
    }


    public function deleteAction($name)
    {
        $name = $this->filter->sanitize($name, "alphanum");
        $this->userData->removeUser($name);
        return $this->responseHandler->negotiate($this->request, []);
	}  

}
?>