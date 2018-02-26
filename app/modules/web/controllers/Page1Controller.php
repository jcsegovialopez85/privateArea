<?php

namespace PrivateArea\Modules\Web\Controllers;

class Page1Controller extends ControllerBase
{
	public function indexAction()
    {
		$this->view->page = "Page1";
	}
}
?>