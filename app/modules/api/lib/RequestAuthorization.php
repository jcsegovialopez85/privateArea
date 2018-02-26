<?php

namespace PrivateArea\Modules\Api\Lib;

class RequestAuthorization{

	protected $request;
	
	public function __construct($request){
		$this->request = $request;
	}

	public function getToken(){
		$headers = $this->request->getHeaders();
		if(!isset($headers["Authorization"])){
			return false;
		}

		return str_replace("Basic ", "", $headers["Authorization"]);
	}

	public function getCredentials($token){
		$token = base64_decode($token);
		return list($name, $password) = explode(':', $token);
	}

}
?>
