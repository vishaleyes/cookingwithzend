<?php

class UserController extends DefaultController  
{
	
	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn( array( 'login', 'new', 'create', 'logout' ) );
	}
	
	public function init()
	{
		$u = new User();
		$this->form = new Zend_Form;
		$this->form->addElements( $u->getFormElements() );
		parent::init();
	}

	public function newAction()
	{
		$this->view->title = 'Create an account';
		$this->view->pageContent = $this->pagesFolder.'/user/new.phtml';
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
		
		if (! $this->form->isValid($_POST)) {
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
			if ( $user = $u->getByEmail( $params['email'] ) )
				$this->session->user = $user->toArray();
			$this->log->info( 'Inserted user ' . $user->name . ' user id : ' . $user->id );
			$this->_redirect( '/' );
			exit;
		} catch(Exception $e) {
			$this->session->error = 'Email address already exists';
			$this->_redirect( '/user/new' );
		}

	}

	/**
	 * Display the users acccount to them
	 */

	public function accountAction()
	{
		$u = new User();
		$rowset = $u->find( $this->_getParam( 'user_id' ) );
		if ( $rowset->current() )
		{
			$this->view->user = $rowset->current()->toArray();
		}
		
		// if we want to change password we can do it later
		$this->form->removeElement('password');
		
		$this->form->getElement('name')->setValue( $this->view->user['name'] );
		$this->form->getElement('email')->setValue( $this->view->user['email'] );
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
		$this->_redirect( '/user/account/user_id/'.$this->_getParam( 'user_id' ) );
	}

	/**
	 * Log the user into the system
	 */

	public function loginAction() {

		$this->view->pageContent = $this->pagesFolder.'/user/login.phtml';
		$this->view->title = 'Login';
		
		// Dont need the username
		$this->form->removeElement( 'name' );
		$this->form->addElement( 'submit', 'Login' );

		if (! $this->form->isValid($_POST)) {
			$this->view->form = $this->form;
			echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
			exit;
		}
		
		$values = $this->form->getValues();

		$this->auth->setIdentity( $values['email'] )
		           ->setCredential( $values['password'] );

		$result = $this->auth->authenticate();
		
		if( $result->isValid() )
		{
			$u = new User();

			if ( $user = $u->getByEmail( $values['email'] ) ) {
				$user->last_login = new Zend_Db_Expr('NOW()');
				$user->save();
				$this->session->user = $user->toArray();
			}
			$this->log->info( 'User '.sq_brackets( $this->session->user['name'] ).' logged in' );
		} else {
			$this->log->info( 'User '.sq_brackets( $values['email'] ).' failed login'. var_export( $result, true ) );
		}
		
		// Redirect to what we were asking for in the fist place or /
		if ( ! empty( $this->session->referrer ) ) {
			$redirect = $this->session->referrer;
			$this->log->info( $redirect );
			unset( $this->session->referrer );
			$this->_redirect( $redirect );
		} else {
			$this->_redirect( '/' );
		}
	}
	
	/**
	 * Logout, nuke the user session
	 */
	
	public function logoutAction()
	{
		unset( $this->session->user );
		$this->_redirect( '/' );
	}
	
	public function postDispatch() {
		exit;
	}
}
