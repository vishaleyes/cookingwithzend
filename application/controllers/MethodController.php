<?php

class MethodController extends Recipe_Model_Controller  
{

	/**
	 * Setup this controller specifically and then call the parent
	 */

	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}
	
	public function newAction()
	{
		if ( ! $this->checkRequiredParams(array('recipe_id')) )
			$this->_redirect( '/recipe/index' );
		
		$recipeModel = new Recipe_Model_Recipe();
		$recipe = $recipeModel->fetchSingleByPrimary($this->_getParam('recipe_id'));
		$this->view->recipe = $recipe;
			
		$this->view->title = 'Create a method';
		$form = $this->model->getForm('Method');
		$form->populate( array( 'recipe_id' => $recipe['id'] ) );
		$this->view->tempConversionForm = $this->model->getForm('TemperatureConversion');
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
								
				$this->model->table->insert( $data );

				$this->_flashMessenger->addMessage( 'Added method' );
				$this->_redirect( '/recipe/view/id/' . $recipe['id'] );
			}
		}
	}
	
	/**
	 * Edit a method item
	 */

	public function editAction()
	{
		// Fetch the recipe being requested
		if ( ! $method = $this->model->fetchSingleByPrimary($this->_id) )
		{
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Unable to find method with id ' . $this->_id );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect( '/recipe/index' );
		}
		
		$this->view->title = 'Edit the instructions';
		
		$form = $this->model->getForm('Method');
		$form->populate($method->toArray());
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				
				$method->setFromArray($data);
				$method->save();

				$this->_flashMessenger->addMessage( 'Edited method' );
			}
			$this->_redirect( '/recipe/view/id/' . $this->_getParam('recipe_id') );
		}
	}

	/**
	 * Delete a method item
	 */

	public function deleteAction()
	{
		if ( ! $method = $this->model->fetchSingleByPrimary($this->_id) )
		{
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Unable to find method with id ' . $this->_id );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect( '/recipe/index' );
		}
		
		$recipe_id = $method->recipe_id;
		$method->delete();
		
		$this->_redirect( '/recipe/view/id/'.$recipe_id );
	}
	
}
