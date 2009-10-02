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
				$ingredientData = array();
				$ingredientData['name'] = $data['name'];
	
				// We start a transaction because we need to insert into two tables
				$this->_db->beginTransaction();
				
				$ingredient = $this->model->table->insert( $ingredientData );
				$m = new Models_Measurement();
				$measurement = $m->getSingleByField('name', $data['measurement']);
								
				$ri = new Models_RecipeIngredient();
						
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
