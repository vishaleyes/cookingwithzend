<?php

class RecipeController extends Recipe_Model_Controller
{

	public function init()
	{
		parent::init();
		$this->_model = $this->getModel();
		$this->_form = $this->getForm();
	}

	public function indexAction()
	{
		$this->view->title = 'Viewing recipes';
		$select = $this->_model->getRecipesSelect(null, 'created', 'DESC');

		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		$paginator->setItemCountPerPage($this->_prefs->getPreference('RecipesPerPage'));
		$paginator->setCurrentPageNumber($this->_getParam('page'));
		$this->view->paginator = $paginator;
	}

	/**
	 * Display the build a new recipe page and accept the results
	 */

	public function newAction()
	{
		$this->view->title = 'Create a recipe';
		$this->view->_form = $this->_form;

		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->_form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $this->_form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				print "I got here..";
				
				$this->_db->beginTransaction();
				try{
					$this->_model->table->insert( $data );
					$id = $this->_db->lastInsertId();
					$this->_db->update("users", array(
						"recipes_count" => new Zend_Db_Expr("(recipes_count + 1)")
					), "id = " . $this->_identity->id);
					$this->_db->commit();
					
					$this->_log->info( 'Added Recipe ' . sq_brackets( $data['name'] ) ); 
					$this->_flashMessenger->addMessage( 'Added recipe ' . $data['name'] );
					$this->_redirect( '/recipe/view/id/'.$id );
				} catch(Exception $e) {
					$this->_db->rollback();
				}

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
		
		$this->_form->populate($recipe->toArray());
		$this->view->_form = $this->_form;
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->_form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $this->_form->getValues();

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
		$this->_model->getRecipe($this->_id);
		
		// If this is being viewed by a guest or not by the creator
		if ( !$this->_acl->isAllowed($this->_identity, $this->_model, 'edit') )
		{
			$this->_model->incrementField('view_count');
			$this->_db->update("recipes", array(
				"view_count" => $this->_model->__get('view_count')
			), "id = " . $this->_id);
		}
		
		$rate = new Recipe_Model_Rating();
		// Only do this if we have a logged in user
		if ( $this->_identity )
		{
			$comment_form = $this->_model->getForm('Comment');
			$rating_form = $this->_model->getForm('Rating');
			$comment_form->populate(array('recipe_id' => $this->_id));
			$rating_form->populate(array('recipe_id' => $this->_id));
			$this->view->comment_form = $comment_form;
			$this->view->rating_form = $rating_form;
			
			// Figure out if this user has made a rating or not
			$this->view->hasRated = $rate->hasRated($this->_id, $this->_identity->id);
		}
		
		$this->view->recipe = $this->_model->toArray();
		$ingredients = $this->_model->getIngredients($this->_id);
		$this->view->ingredients  = $ingredients;
		
		$methods = $this->_model->getMethods($this->_id);
		$this->view->methods  = $methods;
		
		$c = new Recipe_Model_Comment();
		$this->view->comments = $c->getComments('c.recipe_id', $this->_id);
		
		$this->view->ratings = $rate->getRatings($this->_id);
	}
	
	public function userAction()
	{
		if ( ! $this->checkRequiredParams(array('user_id')) )
			$this->_redirect( '/recipe/index' );

		$userID = $this->_getParam('user_id');
		$this->view->title = 'Viewing recipes for user';
		$u = new Recipe_Model_User();
		$user = $u->getSingleByField('name', $userID);
		$this->view->user = $user;

		$select = $this->_model->getRecipesSelect($userID, $this->_getParam('order'), $this->_getParam('direction'));

		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		$paginator->setItemCountPerPage($this->_prefs->getPreference('ItemsPerList'));
		$paginator->setCurrentPageNumber($this->_getParam('page'));

		$this->view->paginator = $paginator;
		$this->view->searchCriteria = new Recipe_SearchCriteria(0, $this->_getParam('order'), $this->_getParam('direction'));
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
			), "id = " . $this->_identity->id);
			$this->_db->commit();
		} catch(Exception $e) {
			$this->_db->rollback();
		}
		
		$this->_redirect( '/recipe/index' );
	}
	
	public function popularAction()
	{
		$this->view->title = 'Most popular recipes';
		$recipes = $this->_model->getRecipes(null, 'view_count', 'DESC');
		$this->view->recipes = $recipes;
		$this->_helper->viewRenderer->setScriptAction('index');
	}

}

