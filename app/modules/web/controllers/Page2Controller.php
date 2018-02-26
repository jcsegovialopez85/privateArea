<?php

namespace PrivateArea\Modules\Web\Controllers;

class Page2Controller extends ControllerBase
{
	public function indexAction()
    {
		$this->view->page = "Page2";
	}
}
?>