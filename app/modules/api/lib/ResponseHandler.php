<?php

namespace PrivateArea\Modules\Api\Lib;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use PrivateArea\Modules\Api\Lib\JsonResponse;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;
use \Negotiation\Negotiator;

class ResponseHandler{

	public function negotiate(Request $request, array $data = [])
	{
		$statusCode = 200;
		$acceptHeader = $request->getHeader('Accept') ? $request->getHeader('Accept') : 'application/json';
		$priorities = ['application/json', 'text/html; charset=UTF-8'];

		$negotiator = new Negotiator();
		$mediaType = $negotiator->getBest($acceptHeader, $priorities);
        $value = $mediaType->getValue();

		if ($value == 'text/html; charset=UTF-8') {
			$response = new Response();
			$response->setContent(print_r($data, true));
			$response->setStatusCode($statusCode);
			return $response;
		}
		return new JsonResponse($data, $statusCode);
	}

}


?>