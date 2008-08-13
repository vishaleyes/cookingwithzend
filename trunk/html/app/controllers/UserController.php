<?php

class UserController extends DefaultController  
{
	
	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn( array( 'new', 'create' ) );
	}
	
	public function init()
	{
		$u = new User();
		$this->form = new Zend_Form;
		$this->form->addElements( $u->getFormElements() );
		parent::init();
	}

	public function confirmAction()
	{
		
	}

	public function newAction()
	{
		$this->view->title = 'Create an account';
		$this->view->pageContent = $this->pagesFolder.'/user/new.phtml';
		$this->form->removeElement( 'open_id' );
		$this->renderModelForm( '/user/create', 'Signup' );
	}
	
	/**
	 * Create a new user
	 * @todo Pass form params back to /user/new
	 */

	public function createAction()
	{
		$this->view->title = 'Create an account';
		$this->view->pageContent = $this->pagesFolder.'/user/new.phtml';
		$this->form->removeElement( 'open_id' );
		
		if (! $this->form->isValid($_POST)) {
			$this->log->debug( 'Form failed '. var_export( $this->form->getMessages(), true ) );
			$this->_redirect( '/user/new' );
		}

		$values = $this->form->getValues();
		$params = array(
			'name'       => $values['name'],
			'email'      => $values['email'],
			'password'   => new Zend_Db_Expr('PASSWORD("'.$values['password'].'")')
		);
		
		try {
			$u = new User();
			$u->insert( $params );
			$this->message->addMessage( 'Please check your email for a confirmation link' );
			$this->log->debug( 'Inserted user ' . $values['email'] );
			$this->_redirect( '/' );
		} catch(Exception $e) {
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( $e->getMessage() );
			$this->message->resetNamespace();
			$this->log->debug( 'Failed to insert user ' .$values['email'] . ' : ' . var_export( $e, true ) );
			$this->_redirect( '/user/new' );
		}

	}

	/**
	 * Display the users acccount to them
	 */

	public function accountAction()
	{
		$u = new User();
		$rowset = $u->find( $this->session->user['id'] );
		if ( $rowset->current() )
		{
			$this->view->user = $rowset->current()->toArray();
		}
		
		// if we want to change password we can do it later
		$this->form->removeElement('password');
		
		$this->form->getElement('name')->setValue( $this->view->user['name'] );
		$this->form->getElement('email')->setValue( $this->view->user['email'] );
		$this->form->getElement('openid')->setValue( $this->view->user['openid'] );
		$this->form->setAction( '/user/update/user_id/' . $this->view->user['id'] );
		$this->form->addElement( 'submit', 'Update' );
		
		
		$this->view->title = 'Your account';
		$this->view->form = $this->form;
		$this->view->pageContent = $this->pagesFolder.'/user/account.phtml';
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
	}
	
	/**
	 * Update the users account
	 */
	
	public function updateAction()
	{
		$this->form->removeElement('password');
		
		if (! $this->form->isValid($_POST)) {
			$this->log->info( var_export( $this->form->getMessages(), true ) );
			$this->_redirect( '/user/account/user_id/'.$this->_getParam( 'user_id' ) );
		}

		$values = $this->form->getValues();
		$params = array(
			'name'    => $values['name'],
			'email'   => $values['email'],
			'updated' => new Zend_Db_Expr('NOW()')
		);
		
		$this->db->update( 'users', $params );
		$this->message->addMessage( 'Details updated' );
		$this->_redirect( '/user/account/user_id/'.$this->_getParam( 'user_id' ) );
	}
	
	public function postDispatch() {
		exit;
	}
}
