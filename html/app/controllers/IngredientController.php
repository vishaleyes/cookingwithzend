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
		if ( ! $this->checkRequiredParams(array('recipe_id')) )
			$this->_redirect( '/recipe/index' );
			
		// fetch the Recipe
		$recipeModel = new Models_Recipe();
		if ( ! $recipe = $this->model->fetchSingleByPrimary($this->_getParam('recipe_id')) )
		{
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Unable to find recipe with id ' . $this->_getParam('recipe_id') );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect( '/recipe/index' );
		}
		$this->view->recipe = $recipe;
		
		$this->view->title = 'Add an ingredient';

		$form = $this->model->getForm('Ingredient');
		$form->populate( array( 'recipe_id' => $recipe['id'] ) );
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
						'recipe_id'     => $recipe['id'],
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
