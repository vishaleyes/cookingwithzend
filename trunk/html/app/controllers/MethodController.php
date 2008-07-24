<?php

class MethodController extends DefaultController  
{
	/**
	 * This happens before the page is dispatched
	 */

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn();
	}

	/**
	 * Setup this controller specifically and then call the parent
	 */

	public function init()
	{
		$m = new MethodItem();
		$this->form = new Zend_Form;
		$this->form->addElements( $m->_form_fields_config );
		$text = $this->form->getElement( 'description' );
		$stripTags = new Zend_Filter_StripTags();
		$stripTags->setTagsAllowed( array( 'p', 'a', 'img', 'strong', 'b', 'i', 'em', 's', 'del' ) );
		$stripTags->setAttributesAllowed( array( 'href', 'target', 'rel', 'name', 'src', 'width', 'height', 'alt', 'title' ) );
		$text->addFilter( $stripTags );

		parent::init();
	}
	
	public function newAction()
	{
		$this->view->title = 'Create a method';
		$this->view->pageContent = $this->pagesFolder.'/method/new.phtml';
		$this->renderModelForm( '/method/create/recipe_id/' . $this->recipe->id, 'Add' );
	}
	
	public function createAction()
	{
		if (! $this->form->isValid($_POST)) {
			$this->_redirect( '/method/new/recipe_id/' . $this->recipe->id );
		}

		$params = $this->form->getValues();
		$params['recipe_id'] = $this->recipe->id;
		
		$m = new MethodItem();
		$r = $this->recipe->getTable();
		
		$this->db->beginTransaction();
		
		// Put the insert into a transaction
		try {
			$m->insert( $params );
			$params = array();
			$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );
			$this->db->commit();
			$this->log->info( 'Added MethodItem to recipe ' . sq_brackets( $this->recipe->id ) ); 
			$this->_redirect( '/recipe/view/recipe_id/' . $this->recipe->id );
		} catch (Exception $e) {
			$this->log->info( $e->getMessage() );
			$this->db->rollBack();
			$this->_redirect( '/method/new/recipe_id/' . $this->recipe->id );
		}

	}

	/**
	 * Edit a method item
	 */

	public function editAction()
	{
		$this->view->title = 'Edit the instructions';
		$this->view->pageContent = $this->pagesFolder.'/method/new.phtml';
		
		$m = new MethodItem();
		$rowset = $m->find( $this->_getParam( 'method_id' ) );
		if (! $rowset)
			$this->_redirect( '/' );

		$method = $rowset->current();
		$values = $method->toArray();
		foreach ( $this->form->getElements() as $element )
		{
			$element->setValue( $values[$element->getName()] );
		}

		$this->renderModelForm( '/method/update/recipe_id/' . $method->recipe_id . '/method_id/'.$method->id, 'Update' );
	}

	/**
	 * Update the method
	 */

	public function updateAction()
	{
		if ( ! $this->form->isValid($_POST) ) {
			$this->log->info( 'Form is not valid '.var_export( $this->form->getMessages(), true ) );
			$this->_redirect( '/recipe/view/recipe_id/' . $this->recipe->id );
		}

		$params = $this->form->getValues();
		$m = new MethodItem();
		$r = $this->recipe->getTable();

		$this->db->beginTransaction();
		try {
			$where = $m->getAdapter()->quoteInto( 'id = ?', $this->_getParam( 'method_id' ) );
			$m->update( $params, $where );
		
			$params = array();
			$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );
			$r->update( $params, $where );
			$this->db->commit();
		} catch (Exception $e) {
			$this->db->rollBack();
			$this->log->info( $e->getMessage() );
		}

		$this->_redirect( '/recipe/view/recipe_id/' . $this->recipe->id );
	}

	/**
	 * Delete a method item
	 */

	public function deleteAction()
	{
		$methodId = $this->_getParam( 'method_id' );
		$where = $this->db->quoteInto( 'id = ?', $methodId );

		$params = array();
		$r = $this->recipe->getTable();

		$this->db->beginTransaction();
		try {
			$this->db->delete( 'method_items', $where );
			$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );
			$r->update( $params, $where );
			$this->db->commit();
		} catch (Exception $e) {
			$this->db->rollBack();
			$this->log->info( $e->getMessage() );
		}
		
		$this->message->addMessage( 'Deleted instructions from '.$this->recipe->name );
		$this->_redirect( '/recipe/view/recipe_id/'.$this->recipe->id );
	}
	
	/**
	 * We are existing after the action is dispatched
	 */

	public function postDispatch() {
		exit;
	}
	
}
