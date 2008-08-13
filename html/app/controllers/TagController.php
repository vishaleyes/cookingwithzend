<?php

class TagController extends DefaultController  
{

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn( array( 'index' ) );
	}

	public function tagsAction()
	{
		$tags = explode( ' ', $this->_getParam( 'name' ) );
		$t = new Tag();
		print_r( $t->getTagObjects( $tags ) );
		
		// Not sure what to do with this view just yet
	}

	/**
	 * Delete a tag from the recipe
	 */

	public function deleteAction()
	{
		$this->db->delete( 'taggings', 'id = '.$this->_getParam('id') );
		// Go back to where we came from
		$this->_redirect( $_SERVER['HTTP_REFERER'] );
	}

	/**
	 * We are existing after the action is dispatched
	 */
	
	public function postDispatch() {
		exit;
	}
	
}
