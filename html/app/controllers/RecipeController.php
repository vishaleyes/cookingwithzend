<?php

class RecipeController extends DefaultController  
{

	/**
	 * This happens before the page is dispatched
	 */

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn( array( 'view', 'index' ) );
		$this->authorised( array( 'edit' ) );
	}

	/**
	 * Setup this controller specifically and then call the parent
	 */

	public function init()
	{
		$r = new Recipe();
		$t = new Tag();
		$this->form = new Zend_Form;
		$this->form->addElements( $r->_form_fields_config );
		$this->form->addElements( $t->_form_fields_config );
		parent::init();
	}

	/**
	 * Display the build a new recipe page
	 */
	
	public function newAction()
	{
		$this->view->title = 'Create a recipe';
		$this->view->pageContent = $this->pagesFolder.'/recipe/new.phtml';
		
		if (! $this->form->isValid($_POST)) {
			$this->renderModelForm( '/recipe/create', 'Add' );
		}
	}

	/**
	 * Main index page, you can optionally 
	 */

	public function indexAction()
	{
		$r = new Recipe();
		$select = $r->select();

		if ( $this->_getParam( 'user_id' ) > 0 )
			$select->where( 'creator_id = ?', $this->_getParam( 'user_id' ) );

		$rowset = $r->fetchAll( $select );

		$rat = new Rating();

		$output = array();
		
		$tag = new Tag();
		$this->view->tags = $tags;

		foreach( $rowset as $row ) {
			$temp = array();
			$temp = $row->toArray();
			$temp['tags'] = $tag->getTags( $row );
			$temp['rating'] = $rat->getRating( $row->id );
			$output[] = $temp;
			
		}

		$this->view->recipes = $output;
		
		$this->view->title = 'Create a recipe';
		$this->view->pageContent = $this->pagesFolder.'/recipe/index.phtml';
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
	}

	/**
	 * Put a new recipe in the database
	 * @todo Pass form params back to /recipe/new if fail
	 */

	public function createAction()
	{
		if (! $this->form->isValid($_POST)) {
			$this->_redirect( '/recipe/new' );
		}

		$params = $this->form->getValues();
		$tags = $params['tag_name'];
		unset( $params['tag_name'] );
		
		$r = new Recipe();
		$row = null;
		$this->db->beginTransaction();
		
		// Put the insert into a transaction
		try {
			$r->insert( $params );
			// grab the last recipe inserted by this user
			$select = $r->select()
			            ->where( 'name = ?', $params['name'] )
			            ->where( 'creator_id = ?', $this->session->user['id'] )
			            ->order( 'created DESC' )
			            ->limit(1);
			            
			$row = $r->fetchRow( $select );

			$t = new Tag();
			$t->splitTags( $tags, $row );

			$this->db->commit();
			$this->log->info( 'Added Recipe ' . sq_brackets( $params['name'] ) . ' by User ' . sq_brackets( $this->session->user['name'] ) ); 
		} catch (Exception $e) {
			$this->log->info( $e->getMessage() );
			$this->db->rollBack();
			$this->_redirect( '/recipe/new' );
		}
		
		$this->message->addMessage( 'Added Recipe ' . sq_brackets( $params['name'] ) );
		$this->_redirect( '/ingredient/new/recipe_id/' . $row->id );
	}

	/**
	 * Display the edit form
	 */

	public function editAction()
	{
		$this->view->title = 'Edit a recipe';
		$this->view->pageContent = $this->pagesFolder.'/recipe/new.phtml';

		$values = $this->recipe->toArray();
		foreach ( $this->form->getElements() as $element )
		{
			$element->setValue( $values[$element->getName()] );
		}
		$this->renderModelForm( '/recipe/update/recipe_id/'.$this->recipe->id, 'edit' );
	}

	/**
	 * Actually update the record
	 */

	public function updateAction()
	{
		if ( ! $this->form->isValid($_POST) ) {
			$this->_redirect( '/recipe/edit/recipe_id/' . $this->recipe->id );	
		}
	
		$params = $this->form->getValues();
		$r = new Recipe();
		$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );
		$r->update( $params, $where );
		
		$this->_redirect( '/recipe/view/recipe_id/' . $this->recipe->id );	
	}

	/**
	 * Delete the recipe, the recipe_ingredients and ratings are caught by DB FK
	 * constraints but tags cannot be (unsure about this) so will do them manually
	 */

	public function deleteAction()
	{
		$name = $this->recipe->name;
		$this->db->beginTransaction();
		try{
			$t = new Tag();
			$t->delete( $this->recipe );
			$this->recipe->delete();
			$this->db->commit();
		} catch (Exception $e) {
			$this->log->info( $e->getMessage() );
			$this->db->rollBack();
		}

		$this->message->addMessage( 'Deleted recipe ' . $name );
		$this->_redirect( '/' );
		
	}

	/**
	 * View the recipe in all its wonderful glory
	 */

	public function viewAction()
	{
		// If the person viewing it is not the creator
		if ( $this->session->user['id'] != $this->recipe->creator_id )
		{
			// Increment the view counter
			$this->recipe->view_count++;
			$this->recipe->save();
		}

		// Fetch things
		$this->view->ingredients = array();
		$this->view->title       = $this->recipe->name;
		$ingredients = $this->recipe->findRecipeIngredient();

		if ( $ingredients )
		{
			foreach( $ingredients as $ingredient ) {
				$this->view->ingredients[] = $ingredient->toArray();
			}
		}
			
		$this->view->recipe      = $this->recipe->toArray();
		$methods                 = $this->recipe->findMethodItem();
		if ( $methods )
			$this->view->methods = $methods->toArray();

		$tag = new Tag();
		$tags = $tag->getTags( $this->recipe );
		$this->view->tags = $tags;

		/*	Submit rating form	*/
		$this->view->submit_rating_form = new Zend_Form();
		$this->view->submit_rating_form->setAction('/rating/add/recipe_id/' . $this->recipe->id);
     	$this->view->submit_rating_form->setMethod('post');
     	$this->view->submit_rating_form->setAttrib('name','submit_rating');
     	$this->view->submit_rating_form->setAttrib('id','submit-rating');
     	$this->view->submit_rating_form->addElement('select','rating_value');
     	
     	for ($i = 1;$i <= Rating::MAX_RATING;$i++)
     	{
     		$this->view->submit_rating_form->rating_value->addMultiOption($i,$i . " out of " . Rating::MAX_RATING);
     	}
     	
     	$submit_button = new Zend_Form_Element_Submit('submit','submit_rating');
     	$submit_button->setLabel('Submit your rating');
     	$this->view->submit_rating_form->addElement($submit_button);

		$this->view->pageContent = $this->pagesFolder.'/recipe/view.phtml';
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));

	}
	
	/**
	 * We are existing after the action is dispatched
	 */

	public function postDispatch() {
		exit;
	}
	
}

