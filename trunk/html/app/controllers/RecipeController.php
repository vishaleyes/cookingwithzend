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
		$this->pendingAccount( array( 'index' ) );
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
		$this->form->addElements( $r->getFormElements() );
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
		
		$this->renderModelForm( '/recipe/create', 'Add' );
	}

	/**
	 * Main index page, you can optionally 
	 */

	public function indexAction()
	{
		$this->view->title = 'Viewing recipes';

		$items_per_page = ( $this->session->pagination['items_per_page'] ? $this->session->pagination['items_per_page'] : 5 );

		$r = new Recipe();
		$select = $r->select();
		$total_select = $this->db->select()->from( 'recipes', array( 'count' => 'COUNT(id)' ) );
		
		if ( $this->_getParam( 'userId' ) ) {
			$u = new User();
			$user = $u->getByField( 'name', $this->_getParam( 'userId' ) );
			$this->view->title = 'Viewing recipes for ' . $user->name;
			$select->where( 'creator_id = ?', $user->id );
			$total_select->where( 'creator_id = ?', $user->id );
		}

		if ( $this->_getParam( 'page' ) > 1 ) {
			$offset = $items_per_page * ($this->_getParam( 'page' ) - 1);
			$select->limit( $items_per_page, $offset );
		} else {
			$select->limit( $items_per_page );
		}

		$stmt = $this->db->query( $total_select );
		$total_entries = $stmt->fetch();
		
		$this->view->pagination_config = array(
			'total_items'    => $total_entries['count'],
		    'items_per_page' => $items_per_page,
			'style'          => 'digg'
		);

		/**
		 * @todo Move this into RecipeRowset perhaps so that the ratings and tags ar in the rowset
		 */
		
		$rowset = $r->fetchAll( $select );

		// Dunno why this is still needed
		foreach( $rowset as $row ) {
		}
		// ----------------

		$this->view->recipes = $rowset->toArray();
		
		$this->view->pageContent = $this->pagesFolder.'/recipe/index.phtml';
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
	}

	/**
	 * Put a new recipe in the database
	 * @todo Pass form params back to /recipe/new if fail
	 */

	public function createAction()
	{
		if ( ( ! $_POST ) || ( ! $this->form->isValid($_POST) ) ) {
			$this->view->pageContent = $this->pagesFolder.'/recipe/new.phtml';
		
			$this->renderModelForm( '/recipe/create', 'Add' );
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
			$user = $row->user->adjustColumn( 'recipes_count', 'increase' );
			
			$t = new Tag();
			$t->splitTags( $tags, $row );

			$this->db->commit();
			$this->log->info( 'Added Recipe ' . sq_brackets( $params['name'] ) . ' by User ' . sq_brackets( $this->session->user['name'] ) ); 
		} catch (Exception $e) {
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'Something went horribly wrong' );
			$this->message->resetNamespace();
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
		$t = new Tag();
		$tags = $t->getTagsAsString( $this->recipe );
		$this->form->getElement( 'tag_name' )->setValue( $tags );

		$this->renderModelForm( '/recipe/update/recipe_id/'.$this->recipe->id, 'edit' );
	}

	/**
	 * Actually update the record
	 */

	public function updateAction()
	{
		if ( (! $_POST) || (! $this->form->isValid($_POST)) ) {
			$this->view->pageContent = $this->pagesFolder.'/recipe/new.phtml';
			$this->renderModelForm( '/recipe/update/recipe_id/'.$this->recipe->id, 'edit' );
		}
	
		$params = $this->form->getValues();
		$tags = $params['tag_name'];
		unset( $params['tag_name'] );


		$r = new Recipe();
		$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );

		$recipe = $this->recipe;

		$this->db->beginTransaction();
		try {
			$r->update( $params, $where );

			// Remove all the taggings
			$this->db->delete( 'taggings', array( 
				'taggable_id = ' . $this->recipe->id,
				'taggable_type = ' . $this->db->quote( $this->recipe->getTableClass() )
			));

			// Insert new ones
			$t = new Tag();
			$t->splitTags( $tags, $this->recipe );
			$this->db->commit();

		} catch (Exception $e) {
			$this->log->info( var_export( $e->getMessages(),true ) );
			$this->db->rollBack();
		}
		
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
			$user = $this->recipe->user->adjustColumn( 'recipes_count', 'decrease' );
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
		$this->view->textfield = true;
		$ingredients = $this->recipe->findRecipeIngredient();

		if ( $ingredients )
		{
			foreach( $ingredients as $ingredient ) {
				$this->view->ingredients[] = $ingredient->toArray();
			}
		}
			
		$this->view->recipe      = $this->recipe->toArray();

		// Fetch the Methods
		$m = new MethodItem();
		$methodSelect = $m->select()
			->where( 'recipe_id = ?', $this->recipe->id )
			->order( array( 'position', 'id' ) );
		
		$methods                 = $m->fetchAll( $methodSelect );
		if ( $methods )
			$this->view->methods = $methods->toArray();

		if ( $this->recipe->ratings_count > 0 ) {
			$r = new Rating();
			$this->view->rating = $r->getRating( $this->recipe->id );
			$this->view->rating_form = $r->getRatingForm();
		}

		$tag = new Tag();
		$tags = $tag->getTags( $this->recipe );
		$this->view->tags = $tags;

		$c = new Comment();
		$this->view->comment_form = $c->getCommentForm( $this->recipe->id );

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

