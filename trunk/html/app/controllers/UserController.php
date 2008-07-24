<?php

class UserController extends DefaultController  
{
	
	public function preDispatch()
	{
		// Held in DefaultController
		//$this->loggedIn( array( 'login', 'new', 'create', 'logout' ) );
	}
	
	public function init()
	{
		$u = new User();
		$this->form = new Zend_Form;
		$this->form->addElements( $u->_form_fields_config );
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
	 * Log the user into the system
	 * @todo Sort out redirect/forward? if coming from another page
	 */

	public function loginAction() {

		$this->view->pageContent = $this->pagesFolder.'/user/login.phtml';
		$this->view->title = 'Login';
		
		// Dont need the username
		$this->form->removeElement( 'name' );
		$this->form->addElement( 'submit', 'Login' );

		if (! $this->form->isValid($_POST)) {
			$this->view->form = $this->form;
			//$this->log->info( 'Form is not valid '.var_export( $this->form->getMessages(), true ) );
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
				$this->session->user = $user->toArray();
			}

			$this->log->info( 'User '.sq_brackets( $this->session->user['name'] ).' logged in' );
		}
		$this->_redirect( '/' );
		/*
		if ( ! empty( $this->session->referrer ) ) {
            $redirect = $this->session->referrer;
            $this->session->referrer = null;
            $this->log->debug( 'Redirecting to : ' . $redirect );
            $this->_redirect( $redirect );
            exit;
        }*/
	}
	
	public function logoutAction()
	{
		$this->session->user = null;
		$this->_redirect( '/' );
	}
	
	public function postDispatch() {
		exit;
	}
}
