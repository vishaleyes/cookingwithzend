<?php

class RecipeController extends DefaultController  
{

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn( array( 'view', 'index' ) );
		$this->authorised( array( 'edit' ) );
	}

	public function init()
	{
		$r = new Recipe();
		$this->form = new Zend_Form;
		$this->form->addElements( $r->_form_fields_config );
		parent::init();
	}
	
	public function newAction()
	{
		$this->view->title = 'Create a recipe';
		$this->view->pageContent = $this->pagesFolder.'/recipe/new.phtml';
		$this->renderModelForm( '/recipe/create', 'Add' );
	}

	/**
	 * Put a new recipe in the database
	 * @todo Pass form params back to /recipe/new
	 */

	public function createAction()
	{
		$this->view->title = 'Create a recipe';
		$this->view->pageContent = $this->pagesFolder.'/recipe/new.phtml';
		
		if (! $this->form->isValid($_POST)) {
			$this->_redirect( '/recipe/new' );
		}

		$params = $this->form->getValues();
		
		$r = new Recipe();
		$row = null;
		$this->db->beginTransaction();
		
		// Put the insert into a transaction
		try {
			$this->log->info( var_export( $params, true ) );
			$r->insert( $params );
			$select = $r->select()
			            ->where( 'name = ?', $params['name'] )
			            ->where( 'creator_id = ?', $this->session->user['id'] )
			            ->order( 'created DESC' )
			            ->limit(1);
			            
			$row = $r->fetchRow( $select );
			$this->db->commit();
			$this->log->info( 'Added Recipe ' . sq_brackets( $params['name'] ) . ' by User ' . sq_brackets( $this->session->user['name'] ) ); 
		} catch (Exception $e) {
			$this->log->info( $e->getMessage() );
			$this->db->rollBack();
			$this->_redirect( '/recipe/new' );
		}
		
		$this->_redirect( '/ingredient/new/recipe_id/' . $row->id );
	}

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

	public function viewAction()
	{
		$this->recipe->view_count++;
		$this->recipe->save();

		$ingredients = $this->recipe->getIngredients();
		$this->view->ingredients = $ingredients;
		$this->view->recipe      = $this->recipe->toArray();
		$methods     = $this->recipe->findMethodItem();
		if ( $methods )
			$this->view->methods = $methods->toArray();
		
		$this->view->title = $this->recipe->name;
		$this->view->pageContent = $this->pagesFolder.'/recipe/view.phtml';

		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));

	}
	
	public function indexAction()
	{
		$this->view->title = 'simplycook.org';
		$this->view->pageContent = $this->pagesFolder.'/recipe/index.phtml';
		
		$r = new Recipe();
		$select = $r->select()->limit( 10 );
		$rowset = $r->fetchAll( $select );
		
		$this->view->recipes = $rowset;
		
	}
	
	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		parent::postDispatch();
		exit;
	}
	
}
