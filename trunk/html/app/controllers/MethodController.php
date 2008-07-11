<?php

class MethodController extends DefaultController  
{

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn();
	}

	public function init()
	{
		$m = new MethodItem();
		$this->form = new Zend_Form;
		$this->form->addElements( $m->_form_fields_config );
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
		$this->view->title = 'Create a method';
		$this->view->pageContent = $this->pagesFolder.'/method/new.phtml';
		
		if (! $this->form->isValid($_POST)) {
			$this->renderModelForm( '/method/create/recipe_id/' . $this->recipe->id, 'Add' );
		}

		$params = $this->form->getValues();
		$params['recipe_id'] = $this->recipe->id;
		
		$m = new MethodItem();
		$row = null;
		$this->db->beginTransaction();
		
		// Put the insert into a transaction
		try {
			$m->insert( $params );
			$this->db->commit();
			$this->log->info( 'Added MethodItem to recipe ' . sq_brackets( $ithis->recipe->id ) ); 
			$this->_redirect( '/recipe/view/recipe_id/' . $ithis->recipe->id );
		} catch (Exception $e) {
			$this->log->info( $e->getMessage() );
			$this->db->rollBack();
			$this->renderModelForm( '/method/create/recipe_id/' . $this->recipe->id, 'Add' );
		}

	}
	
	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}
