<?php

class RecipeController extends DefaultController
{

	public function indexAction()
	{
		$this->view->title = 'Viewing recipes';
		
		$select = $this->model->table->select()
			->limit('30');

		$this->view->recipes = $this->model->table->fetchAll($select);
	}

	/**
	 * Display the build a new recipe page and accept the results
	 */

	public function newAction()
	{
		$this->view->title = 'Create a recipe';
		$form = $this->model->getForm('RecipeNew');
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				
				$this->model->table->save( $data );

				$this->log->info( 'Added Recipe ' . sq_brackets( $data['name'] ) 	); 
				$this->message->addMessage( 'Added recipe ' . $data['name'] );
			}
		}
		$this->_redirect( '/recipe/new' );
	}

	/**
	 * Edit and Update the recipe
	 */

	public function editAction()
	{
		// Fetch the recipe being requested
		$recipe = $ths->model->fetchRecipe($this->_getParam('id'));
		
		$this->view->title = 'Editing recipe - '.$recipe->name;
		
		$form = $this->model->getForm('RecipeNew');
		$form->populate($recipe->toArray());
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				
				$this->model->table->save( $data );

				$this->log->info( 'Edited Recipe ' . sq_brackets( $data['name'] ) 	); 
				$this->message->addMessage( 'Edited recipe ' . $data['name'] );
			}
		}
		$this->_redirect( '/recipe/view/id/' . $this->_getParam('id') );
	}

	/**
	 * View the recipe in all its wonderful glory
	 */

	public function viewAction()
	{
		$recipe = $this->model->fetchRecipe($this->_getParam('id'));
		
		$this->view->recipe  = $recipe->toArray();
	}

}

