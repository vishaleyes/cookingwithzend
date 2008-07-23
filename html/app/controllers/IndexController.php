<?php

class IndexController extends DefaultController  
{

	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}

	/**
	 * A default action
	 *
	 */

	public function indexAction() {
		$this->_redirect( '/recipe/index' );
	}

}
