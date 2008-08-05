<?php


/**
 * Handles the comment system.
 * @author punky
 */

class CommentController extends DefaultController 
{
	
	/**
	 * This happens before the page is dispatch
	 */

	public function preDispatch() {
		
		// Held in DefaultController. Params are actions allowed as guest.
		$this->loggedIn( array( ) );

	}

	public function indexAction() {
	}
	
	public function addAction() {
		
		/* Get request variables */	

		$params = array(
			'recipe_id'   => $this->recipe->id,
			'comment'     => $this->_getParam('comment_value'),
		);

		
		/*	Try and insert them into DB	*/
		try {
			$c = new Comment();
			$c->insert($params);
			
			/* Incease comment counts */
			$this->db->update("users",array("comments_count" => new Zend_Db_Expr("(comments_count + 1)")),"id = " . $this->session->user['id']);
			$this->db->update("recipes",array("comments_count" => new Zend_Db_Expr("(comments_count + 1)")),"id = " . $params['recipe_id']);
			
			
			/* Set successful notice */
			$this->message->setNamespace( 'notice' );
			$this->message->addMessage( 'Comment added successful.' );
			
			/*	If successful go back to last recipe		*/
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
			
			exit;
			
		} catch(Exception $e) {
			
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'Something went horribly wrong' );
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
