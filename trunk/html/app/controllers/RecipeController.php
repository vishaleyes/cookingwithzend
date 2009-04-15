<?php

class RecipeController extends DefaultController
{

	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}

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
		$form = $this->model->getForm('Recipe');
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

				$this->_log->info( 'Added Recipe ' . sq_brackets( $data['name'] ) ); 
				$this->_flashMessenger->addMessage( 'Added recipe ' . $data['name'] );
				$this->_redirect( '/recipe/new' );
			}
		}
	}

	/**
	 * Edit and Update the recipe
	 */

	public function editAction()
	{
		// Fetch the recipe being requested
		if ( ! $recipe = $this->model->fetchSingleByPrimary($this->_id) )
		{
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Unable to find recipe with id ' . $id );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect( '/recipe/index' );
		}
		
		$this->view->title = 'Editing recipe - '.$recipe->name;
		
		$form = $this->model->getForm('Recipe');
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
				
				$recipe->setFromArray($data);
				$recipe->save();

				$this->_log->info( 'Edited Recipe ' . sq_brackets( $data['name'] ) 	); 
				$this->_flashMessenger->addMessage( 'Edited recipe ' . $data['name'] );
			}
			$this->_redirect( '/recipe/view/id/' . $this->_getParam('id') );
		}
	}

	/**
	 * View the recipe in all its wonderful glory
	 */

	public function viewAction()
	{
		if ( ! $recipe = $this->model->fetchSingleByPrimary($this->_id) )
		{
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Unable to find recipe with id ' . $id );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect( '/recipe/index' );
		}
			
		$this->view->recipe  = $recipe->toArray();
		$ingredients = $this->model->getIngredients($recipe['id']);
		$this->view->ingredients  = $ingredients;
		
		$methods = $this->model->getMethods($recipe['id']);
		$this->view->methods  = $methods;
		
	}

}

