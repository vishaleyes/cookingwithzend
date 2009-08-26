<?php


/**
 * Handles the comment system.
 * @author punky
 */

class CommentController extends DefaultController 
{
	
	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}
	
	public function newAction()
	{
		$recipe_id = $this->_getParam('recipe_id');
		$form = $this->model->getForm('Comment');
		$form->populate(array('recipe_id' => $recipe_id));
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// 	if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				
				$this->_db->beginTransaction();
				try {
					$this->model->table->insert( $data );
						
					$counterData = array("comments_count" => new Zend_Db_Expr("(comments_count + 1)"));
					
					$this->_db->update("users", $counterData, "id = " . $this->_identity['id']);
					$this->_db->update("recipes", $counterData, "id = " . $recipe_id);

					$this->_flashMessenger->addMessage( 'Comment added' );
					$this->_db->commit();
				} catch(Exception $e) {
					$this->_db->rollback();
					$this->_log->info( 'Failed to add comment to recipe ' . sq_brackets( $recipe_id ) . ' ' . $e->getMessage() );
				}
			}
		}
		
		$this->_redirect('/recipe/view/id/'.$recipe_id);
		
	}
	
	public function deleteAction()
	{
		$id = $this->_getParam( 'id' );
		$c = new Comment();
		$rowset = $c->find( $id );
		if ($rowset)
			$rowset->current()->delete();

		// go back :)
		$this->_redirect( $_SERVER['HTTP_REFERER'] );
	}
	
}
