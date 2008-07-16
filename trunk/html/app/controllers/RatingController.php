<?php


/**
 * Handles the ratings system for recipe.
 * @author punky
 */

class RatingController extends DefaultController 
{
	
	/**
	 * This happens before the page is dispatch
	 */

	public function preDispatch() {
		// $this->login();
	}

	public function indexAction() {
	}
	
	public function addAction() {
		
		$rating_message = "No rating or recipe specified";
		
		$this->view->pageContent = $this->pagesFolder.'/rating/add.phtml';
		
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
		
	}

	/**
	 * This happens after the page is dispatch
	 */

	public function postDispatch() {
		exit;
	}
}
