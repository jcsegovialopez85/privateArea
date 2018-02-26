<?php 

namespace PrivateArea\Modules\Api\Lib;

use Phalcon\Http\Response;

class JsonResponse extends Response{

	public function __construct($data, $statusCode){
		parent::__construct();
		$this->setContent(json_encode($data));
		$this->setStatusCode($statusCode);
		$this->setContentType("application/json");
	}
}

?>
