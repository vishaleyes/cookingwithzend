<?php

require_once( APPLICATION_PATH . '/controllers/DefaultController.php' );

class LoginController extends DefaultController
{

	public function init()
	{
		$u = new Models_User();
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
		$this->form->removeElement( 'open_id' );
		$this->form->addElement( 'submit', 'Login' );


		if ( ( ! $_POST ) || ( ! $this->form->isValid($_POST) ) ) {
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
			$user = $u->getByField( 'email', $values['email'] );
			
			$msg = $user->checkStatus();
			
			if ( $msg != false )
			{
				$this->log->info('User '.sq_brackets( $this->session->user['name'] ).' tried to login but got ' . sq_brackets( $msg ) );
				$this->message->setNamespace( 'error' );
				$this->message->addMessage( $msg );
				$this->message->resetNamespace();
				$this->_redirect('/');
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
			if ( $user = $u->getByField('openid', htmlspecialchars($id) ) ) {
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

	public function resetAction()
	{
		$this->view->pageContent = $this->pagesFolder.'/login/password-reset.phtml';
		$this->view->title = 'Reset your password';
		
		// Dont need the username
		$this->form->removeElement( 'name' );
		$this->form->removeElement( 'open_id' );
		$this->form->removeElement( 'password' );
		$this->form->addElement( 'submit', 'Password' );

		$this->view->form = $this->form;

		if ( $_POST )
		{
			$u = new User();
			$user = $u->getByField( 'email', $_POST['email'] );
			if ( $user ) {
				$user->forgottenPasswordMail();
				$this->message->addMessage( 'We have sent you an email of the new password to the address ' . $user->email );
			} else {
				$this->message->addMessage( 'Unable to find your Email address in our system, ensure you typed it correctly' );
			}
			$this->_redirect( '/' );
		} else {
			echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
			exit;
		}
	}
	
	public function postDispatch() {
		exit;
	}


}

