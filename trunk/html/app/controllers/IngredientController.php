<?php

class IngredientController extends DefaultController  
{
	
	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}
	
	/**
	 * Display the build a new ingredient page
	 */
	
	public function newAction()
	{
		$this->view->title = 'Add an ingredient';
		
		if ( ! $this->checkRequiredParams(array('recipe_id')) )
			$this->_redirect( '/recipe/index' );
			
		$recipeID = $this->_getParam('recipe_id');

		$form = $this->model->getForm('Ingredient');
		$form->populate( array( 'recipe_id' => $recipeID ) );
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );

				// We start a transaction because we need to insert into two tables
				$this->_db->beginTransaction();
				
				$ingredient = $this->model->table->insert( $data );
				$ri = new Models_RecipeIngredient();
						
				try {
					$ri->table->insert( array(
						'recipe_id'     => $recipeID,
						'ingredient_id' => $ingredient->id
					));
					$this->_db->commit();
				} catch (Exception $e) {
					$this->_db->rollback();
					$this->_log->debug($e->getMessage());
				}
				
				$this->_redirect('/recipe/view/id/'.$recipe['id']);
			}
		}
	}
	
}
