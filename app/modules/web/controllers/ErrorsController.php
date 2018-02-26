<?php
namespace PrivateArea\Modules\Web\Controllers;

use Phalcon\Mvc\Controller;

class ErrorsController extends ControllerBase
{
	public function show404action(){
		$this->response->setStatusCode(404, "Not found");
	}

	public function show401Action()
    {
		$this->response->setStatusCode(401, "Unauthorized");
    }

	public function show500Action(){
		$this->view->disable();
		$this->response->setStatusCode(500, "Internal Server Error");
	}
}