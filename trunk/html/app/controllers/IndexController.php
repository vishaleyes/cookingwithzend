<?php

class IndexController extends Zend_Controller_Action
{

	/**
	 * A default action
	 */

	public function indexAction()
	{
		$this->_redirect('/recipe/index');
	}
	
}
