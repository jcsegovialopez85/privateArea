<?php

namespace PrivateArea\Modules\Web\Controllers;

class Page3Controller extends ControllerBase
{
	public function indexAction()
    {
		$this->view->page = "Page3";
	}
}
?>