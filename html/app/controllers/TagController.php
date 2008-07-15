<?php

class TagController extends DefaultController  
{

	public function indexAction()
	{
		$tags = explode( ' ', $this->_getParam( 'name' ) );
		$t = new Tag();
		print_r( $t->getTagObjects( $tags ) );
	}

	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}
