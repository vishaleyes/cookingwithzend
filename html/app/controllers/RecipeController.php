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
		
		$recipes = $this->model->getRecipes(null, 'created', 'DESC');
		$this->view->recipes = $recipes;
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
				
				$this->_db->beginTransaction();
				try{
					$this->model->table->insert( $data );
					$this->_db->update("users", array(
						"recipes_count" => new Zend_Db_Expr("(recipes_count + 1)")
					), "id = " . $this->_identity['id']);
					$this->_db->commit();
				} catch(Exception $e) {
					$this->_db->rollback();
				}

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
		$recipe = $this->_getSingleRecipe();
		
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
				
				$data = array_merge($recipe->toArray(), $data);
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
		$recipe = $this->model->getRecipe($this->_id);
		// If this is being viewed by a guest or not by the creator
		
		if ( !$this->_identity || ($this->_identity['id'] != $recipe['creator_id']))
		{
			$this->_db->update("recipes", array(
				"view_count" => new Zend_Db_Expr("(view_count + 1)")
			), "id = " . $recipe['id']);
		}
		
		// The comment form only gets made if we are logged in
		if ( $this->_identity )
		{
			$form = $this->model->getForm('Comment');
			$form->populate(array('recipe_id' => $this->_id));
			$this->view->form = $form;
		}
		
		$this->view->recipe  = $recipe;
		$ingredients = $this->model->getIngredients($this->_id);
		$this->view->ingredients  = $ingredients;
		
		$methods = $this->model->getMethods($this->_id);
		$this->view->methods  = $methods;
		
		$c = new Models_Comment();
		$this->view->comments = $c->getComments('c.recipe_id', $this->_id);
		
		
	}
	
	public function userAction()
	{
		if ( ! $this->checkRequiredParams(array('user_id')) )
			$this->_redirect( '/recipe/index' );

		$userID = $this->_getParam('user_id');
		$this->view->title = 'Viewing recipes for user';
		$u = new Models_User();
		$user = $u->getSingleByField('name', $userID);
		$this->view->user = $user;

		$recipes = $this->model->getRecipes($userID, $this->_getParam('order'), $this->_getParam('direction'));
		$this->view->searchCriteria = new Recipe_SearchCriteria(0, $this->_getParam('order'), $this->_getParam('direction'));
		$this->view->recipes = $recipes;
	}
	
	public function deleteAction()
	{
		$recipe = $this->_getSingleRecipe();
		$this->_flashMessenger->addMessage( 'Deleted recipe : ' . $recipe->name );
		$this->_db->beginTransaction();
		try{
			$recipe->delete();
			$this->_db->update("users", array(
				"recipes_count" => new Zend_Db_Expr("(recipes_count - 1)")
			), "id = " . $this->_identity['id']);
			$this->_db->commit();
		} catch(Exception $e) {
			$this->_db->rollback();
		}
		
		$this->_redirect( '/recipe/index' );
	}
	
	public function popularAction()
	{
		$this->view->title = 'Most popular recipes';
		$recipes = $this->model->getRecipes(null, 'view_count', 'DESC');
		$this->view->recipes = $recipes;
		$this->_helper->viewRenderer->setScriptAction('index');
	}

	private function _getSingleRecipe($redirect = '/recipe/index')
	{
		// Fetch the recipe being requested
		if ( ! $recipe = $this->model->fetchSingleByPrimary($this->_id) )
		{
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Unable to find recipe with id ' . $id );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect( '/recipe/index' );
			return false;
		}
		return $recipe;
	}
}

