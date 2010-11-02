<?php

class IngredientController extends Recipe_Model_Controller  
{
	
	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
		$this->form = $this->model->getForm('Ingredient');
		$this->view->form = $this->form;
	}
	
	public function editAction()
	{
		if ( ! $this->checkRequiredParams(array('recipe_id','ingredient_id')) )
			$this->_redirect( '/recipe/index' );
		
		$recipeID = $this->_getParam('recipe_id');
		$ingredientID = $this->_getParam('ingredient_id');
		$ingredient = $this->model->getIngredientForRecipe($recipeID, $ingredientID);
		$this->form->populate($ingredient);
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $this->form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				$ingredientData = array();
				$ingredientData['name'] = $data['name'];
	
				// We start a transaction because we need to insert into two tables
				$this->_db->beginTransaction();
				
				$ingredient = $this->model->table->insert( $ingredientData );
				$m = new Recipe_Model_Measurement();
				$measurement = $m->getSingleByField('name', $data['measurement']);
								
				$ri = new Recipe_Model_RecipeIngredient();
						
				try {
					$ri->table->update( array(
						'recipe_id'      => $recipeID,
						'ingredient_id'  => $ingredient->id,
						'measurement_id' => $measurement['id'],
						'quantity'       => ($data['quantity'] > 0 ? $data['quantity'] : null),
						'amount'         => ($data['amount'] > 0 ? $data['amount'] : null)  
					), "recipe_id = $recipeID AND ingredient_id = $ingredientID");
					$this->_db->commit();
					$this->_flashMessenger->addMessage( 'Updated ingredient '.$ingredient->name );
					$this->_redirect('/recipe/view/id/'.$recipeID);
				} catch (Exception $e) {
					$this->_db->rollback();					
					$this->_log->debug($e->getMessage());
					if( strstr($e->getMessage(), 'Duplicate') )
					{
						$this->_flashMessenger->setNamespace( 'error' );
						$this->_flashMessenger->addMessage( $ingredient->name . ' already exists in this recipe' );
						$this->_flashMessenger->resetNamespace();
					}
					
				}
				
				
			}
		}
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
		
		$this->form->populate( array( 'recipe_id' => $recipeID ) );
		$this->view->form = $this->form;
	
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $this->form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				$ingredientData = array();
				$ingredientData['name'] = $data['name'];
	
				// We start a transaction because we need to insert into two tables
				$this->_db->beginTransaction();
				
				$ingredient = $this->model->table->insert( $ingredientData );
				$m = new Recipe_Model_Measurement();
				$measurement = $m->getSingleByField('name', $data['measurement']);
								
				$ri = new Recipe_Model_RecipeIngredient();
						
				try {
					$ri->table->insert( array(
						'recipe_id'      => $recipeID,
						'ingredient_id'  => $ingredient->id,
						'measurement_id' => $measurement['id'],
						'quantity'       => ($data['quantity'] > 0 ? $data['quantity'] : null),
						'amount'         => ($data['amount'] > 0 ? $data['amount'] : null)  
					));
					$this->_db->commit();
					$this->_flashMessenger->addMessage( 'Added ingredient '.$ingredient->name );
					$this->_redirect('/recipe/view/id/'.$recipeID);
				} catch (Exception $e) {
					$this->_db->rollback();					
					$this->_log->debug($e->getMessage());
					if( strstr($e->getMessage(), 'Duplicate') )
					{
						$this->_flashMessenger->setNamespace( 'error' );
						$this->_flashMessenger->addMessage( $ingredient->name . ' already exists in this recipe' );
						$this->_flashMessenger->resetNamespace();
					}
					
				}
				
				
			}
		}
	}
	
}
