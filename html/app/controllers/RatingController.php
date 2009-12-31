<?php


/**
 * Handles the ratings system for recipe.
 * @author punky
 */

class RatingController extends DefaultController 
{
	
	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}

	// @todo this action should be limited to the person who owns the rating
	
	public function deleteAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$where = 'user_id = '.$this->_getParam('user_id')
			. ' AND recipe_id = ' . $this->_getParam('recipe_id');
			
		$this->_db->beginTransaction();
		try {
			$this->model->table->delete($where);
			
			$ratingsData = array("ratings_count" => new Zend_Db_Expr("(ratings_count - 1)"));
			
			$this->_db->update("users",
				$ratingsData,
				"id = " . $this->_identity['id']
			);
			$this->_db->update("recipes",
				$ratingsData,
				"id = " . $this->_getParam('recipe_id')
			);
			
			$this->_flashMessenger->addMessage( 'Rating deleted' );
				
			$this->_db->commit();
		} catch (Exception $e) {
			$this->_db->rollback();
			$this->_log->debug( 'Error deleting rating to recipe '.$this->_getParam('recipe_id') . ' : ' . $e->getMessage() );
		}
		
		$this->_redirect( '/recipe/view/id/' . $this->_getParam('recipe_id') );
	}
	
	public function newAction()
	{
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
				
		$form = $this->model->getForm('Rating');
		$form->populate($this->_getAllParams());
		$data = $form->getValues();
	
		// Unset the buttons
		unset( $data['submit'] );
				
		/*	Try and insert them into DB	using a transaction */
		$this->_db->beginTransaction();
		try {
			$this->model->table->insert($data);
			
			$ratingsData = array("ratings_count" => new Zend_Db_Expr("(ratings_count + 1)"));
			
			/* Incease ratings counters (maybe this should be done in the table insert? */
			$this->_db->update("users",
				$ratingsData,
				"id = " . $this->_identity['id']
			);
			$this->_db->update("recipes",
				$ratingsData,
				"id = " . $data['recipe_id']
			);
			
			$this->_db->commit();
			/*	If successful go back to last recipe		*/
			
			
		} catch(Exception $e) {
			/*	Broken constraints = throws exception. Display error and return to recipe		*/
			$this->_db->rollback();
			$this->_log->debug( 'Error adding a rating to recipe '.$data['recipe_id'] . ' : ' . $e->getMessage() );
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'There has been an error adding this rating' );
		}
		$this->_redirect( '/recipe/view/id/' . $data['recipe_id'] );
	}
	
}
