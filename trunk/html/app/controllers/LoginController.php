<?php

class LoginController extends DefaultController
{
	public function predispatch()
	{
		// held in defaultcontroller
		// $this->loggedin( array( 'login', 'logout' ) );
	}

	public function init()
	{
		$u = new User();
		$this->form = new Zend_Form;
		$this->form->addElements( $u->getFormElements() );
		
		$e = new Zend_Form_Element( 'text' );
		$e->setRequired( true )
		  ->setLabel( 'OpenID' )
		  ->setName('openid')
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_StringLength( array(3,255) ) );
		
		$this->openid = $e;
		
		
		parent::init();
	}

	public function loginAction() {

		$this->view->pageContent = $this->pagesFolder.'/login/login.phtml';
		$this->view->title = 'Login';
		
		// Dont need the username
		$this->form->removeElement( 'name' );
		$this->form->addElement( 'submit', 'Login' );

		if ( ! $this->form->isValid($_POST) ) {
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

			$user = User::getByEmail( $values['email'] );
			
			if ( ! $user->confirmed )
			{
				$this->message->setNamespace( 'error' );
				$this->message->addMessage( 'You have not confirmed your account, did you click the link in the email?' );
				$this->redirect();
			}
			
			// We should be okay from hereon in
			$user->last_login = new Zend_Db_Expr('NOW()');
			$user->save();
			$this->session->user = $user->toArray();
			
			$this->log->info( 'User '.sq_brackets( $this->session->user['name'] ).' logged in' );
			$this->redirect();
		} else {
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'Wrong credentials supplied.  maybe you forgot your password?' );
			$this->log->debug( 'User '.sq_brackets( $values['email'] ).' failed login'. var_export( $result, true ) );
			$this->redirect();
		}
		
	}

	private function redirect()
	{
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

	public function openidAction()
	{
		$this->view->pageContent = $this->pagesFolder.'/login/openid.phtml';
		$this->view->title = 'OpenID Authentication';
		
		// Dont need the username
		$this->form = new Zend_Form();
		$this->form->addElement( $this->openid );
		$this->form->addElement( 'submit', 'Login' );

		if ( ! $this->form->isValid($_POST) ) {
			$this->view->form = $this->form;
			echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
			exit;
		}
		
		$values = $this->form->getValues();
		$consumer = new Zend_OpenId_Consumer();
		if (!$consumer->login( $values['openid'], '/login/openidcapture')) {
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'OpenID Login failed' );
			$this->redirect();
		}
	}

	public function openidcaptureAction()
	{
		$u = new User;
		$consumer = new Zend_OpenId_Consumer();
		if ($consumer->verify($_GET, $id)) {
			if ( $user = $u->getByOpenID( htmlspecialchars($id) ) ) {
				$user->last_login = new Zend_Db_Expr('NOW()');
				$user->save();
				$this->session->user = $user->toArray();
				
				$this->log->info( 'User '.sq_brackets( $this->session->user['name'] ).' logged in' );
			} else {
				$this->message->setNamespace( 'error' );
				$this->message->addMessage( 'We have been unable to locate your OpenID, did you set it up?' );	
				$this->log->debug( 'User '.sq_brackets( $values['email'] ).' failed login'. var_export( $result, true ) );
			}
		} else {
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'OpenID told us they are not happy with you :)' );
		}
		$this->redirect();
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

