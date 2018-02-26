<?php

namespace PrivateArea\Modules\Api\Lib;
use Phalcon\Http\Request;

class RequestContent{
	protected $request;
	protected $acceptedContentType = ["application/json", "application/x-www-form-urlencoded"];

	public function __construct($request){
		$this->request = $request;
	}

	public function canDeserializeContent(){
		$contentType = $this->request->getContentType();
		return in_array($contentType, $this->acceptedContentType);
	}

	public function getContent(){
		$contentType = $this->request->getContentType();
		if($contentType == "application/json"){
			return $this->request->getJsonRawBody();
		}

		return (object)$this->request->get();
	}
}
?>