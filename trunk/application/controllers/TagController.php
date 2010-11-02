<?php

class TagController extends Recipe_Model_Controller  
{

	public function preDispatch()
	{
		// Held in Recipe_Model_Controller
		$this->loggedIn( array( 'index', 'tags' ) );
		$this->pendingAccount( array( 'index' ) );
	}

	public function tagsAction()
	{
		$tags = $this->_getParam( 'name' );
		$t = new Tag();
		$this->view->recipes = $t->getTagObjects( $tags );
		$this->view->tags = $tags;

		#print_r( $tags );
		#print_r( $this->view->recipes );
		
		// Not sure what to do with this view just yet
		$this->view->pageContent = $this->pagesFolder.'/tags/index.phtml';
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
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
