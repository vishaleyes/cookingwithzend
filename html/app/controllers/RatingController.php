<?php


/**
 * Handles the ratings system for recipe.
 * @author punky
 */

class RatingController extends DefaultController 
{
		
	public function addAction() {
		
		/*	Poss remove - check messages to user with Chris
		$this->view->rating_message = "No rating or recipe specified"; */
		
		/* Get request variables */	

		$params = array(
			'recipe_id'   => $this->recipe->id,
			'value'       => $this->_getParam('rating_star'),
		);

		
		/*	Try and insert them into DB	*/
		try {
			$r = new Rating();
			$r->insert($params);
			
			/* Incease ratings counters */
			$this->db->update("users",array("ratings_count" => new Zend_Db_Expr("(ratings_count + 1)")),"id = " . $this->session->user['id']);
			$this->db->update("recipes",array("ratings_count" => new Zend_Db_Expr("(ratings_count + 1)")),"id = " . $params['recipe_id']);
			
			/*	If successful go back to last recipe		*/
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
			
			exit;
			
		} catch(Exception $e) {
			/*	Broken constraints = throws exception. Display error and return to recipe		*/
			$this->log->debug( $e->getMessage() );
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'You have already rated this recipe.' );
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
		}
		
		
	}
	
}
