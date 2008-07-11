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
		if ( ! $this->session->user ) {
			$this->_redirect( '/user/login' );
		} else {
			$this->_redirect( '/recipe/new' );
		}
	}

}
