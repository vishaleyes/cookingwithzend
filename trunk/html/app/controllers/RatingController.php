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
		
		// Held in DefaultController. Params are actions allowed as guest.
		$this->loggedIn( array( 'view', 'index' ) );

	}

	public function indexAction() {
	}
	
	public function addAction() {
		
		/*	Poss remove - check messages to user with Chris
		$this->view->rating_message = "No rating or recipe specified"; */
		
		
		
		/* Get request variables */	

		$params = array(
			'recipe_id'   => $this->recipe->id,
			'value'       => $this->_getParam('rating_value'),
		);

		
		/*	Try and insert them into DB	*/
		try {
			$r = new Rating();
			$r->insert($params);
			
			/*	If successful go back to last recipe		*/
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
			
			exit;
			
		} catch(Exception $e) {
			/*	Broken constraints = throws exception. Display error and return to recipe		*/
			$this->session->error = 'You have already rated this recipe.';
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
		}
		
		
	}
	
	
	

	/**
	 * This happens after the page is dispatch
	*/ 

	public function postDispatch() {
		exit;
	}
}
