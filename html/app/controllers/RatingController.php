<?php



/*

@author punky

Handles the ratings system for recipe.

*/

class RatingController extends DefaultController 
{
	
	public function indexAction() {
		
		
	}
	
	public function addAction() {
		
		
		$rating_message = "No rating or recipe specified";
		
		$this->view->pageContent = $this->pagesFolder.'/rating/add.phtml';
		
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
				
		
	}
}