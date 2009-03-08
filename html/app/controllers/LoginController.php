<?php

class LoginController extends DefaultController
{

	public function init()
	{
		parent::init();
		$this->model = new Models_User();
	}

	public function indexAction() {

		$this->view->title = 'Login';
		$auth = Zend_Auth::getInstance();
		print_r($auth->getIdentity());

		$form = $this->model->getForm('Login');
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				$result = $this->model->login( $data['email'], $data['password']);
				// Not a valid login
				if( ! $result->isValid() ) {
					print_r($result);
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( 'Wrong credentials supplied.  maybe you forgot your password?' );
					$this->_log->debug( 'User ' . sq_brackets( $data['email'] ).' failed login'. var_export( $result, true ) );
					//$this->redirect();
				}
				
				$email = $result->getIdentity();
				$user = $this->model->getByField('email', $email);

				$auth->getStorage()->write($user->toArray());


				
			}
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

}

