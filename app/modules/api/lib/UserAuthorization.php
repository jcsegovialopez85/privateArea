<?php

namespace PrivateArea\Modules\Api\Lib;

class UserAuthorization{

	protected $request;
	protected $user;

	public function __construct($request, $user){
		$this->request = $request;
		$this->user = $user;
	}

	public function isAuthorized(){
		$method = $this->request->getMethod();

		if($method == "GET"){
			if(!$this->user->hasRole("READ_API")){
				$this->response->setStatusCode(401, "Unauthorized");
				return false;
			}
		}else if($method == "POST" || $method == "PUT" || $method == "DELETE"){
			if(!$this->user->hasRole("WRITE_API")){
				$this->response->setStatusCode(401, "Unauthorized");
				return false;
			}
		}
		return true;
	}

}



?>
