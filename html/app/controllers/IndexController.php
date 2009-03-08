<?php

require_once( APPLICATION_PATH . '/controllers/DefaultController.php' );

class IndexController extends DefaultController
{

	/**
	 * A default action
	 */

	public function indexAction()
	{
		$this->_redirect('/recipe/index');
	}
	
}
