<?php

class LoginController extends DefaultController
{

	public function init()
	{
		parent::init();
		$this->model = new Models_User();
	}

	public function indexAction() 
	{

		$this->view->title = 'Login';

		// If we are already logged in we dont need to login again
		if ( $this->_identity )
			$this->_redirect('/');
		
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
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( 'Wrong credentials supplied.  maybe you forgot your password?' );
					$this->_log->debug( 'User ' . sq_brackets( $data['email'] ).' failed login'. var_export( $result, true ) );
					$this->_redirect('/');
				}
				
				$email = $result->getIdentity();
				$userRow = $this->model->getUserByEmail($email);
				$msg = $this->model->checkStatus($userRow['status']);
				$this->_db->update(
					'users', 
					array('last_login' => new Zend_Db_Expr('NOW()')),
					'id = '.$userRow['id']
				);
				
				// Check to see the user is fully logged in
				$auth = Zend_Auth::getInstance();
				if ( $msg == '' )
				{
					$auth->getStorage()->write($userRow);
				} else {
					// Not logged in, clear the identity
					$auth->clearIdentity();
					$this->_log->info('User '.sq_brackets( $userRow['name'] ).' tried to login but got ' . sq_brackets( $msg ) );
                    $this->_flashMessenger->setNamespace( 'error' );
                    $this->_flashMessenger->addMessage( $msg );
                    $this->_flashMessenger->resetNamespace();
				}
				$this->_redirect('/');
			}
		}
	}
	
	/**
	 * Logout of the system by clearing the identity
	 */
	
	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect('/');
	}

	/**
	 * Executes sendConfirmationEmail function.
	 */
	public function sendConfirmationAction()
	{
		$this->view->title = 'Resend your confirmation';
		$form = $this->model->getForm('Login');
		$this->_helper->viewRenderer->setScriptAction('index');
		
		$form->removeElement( 'password' );
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				$data = $form->getValues();
			
				$user = $this->model->getUserByEmail($data['email']);
				
				if ($user)
				{
					$this->model->sendConfirmationEmail($user);
					$this->_flashMessenger->addMessage( 'Confirmation e-mail sent to '.$user['email'] );
					$this->_redirect('/');
				} else {
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( 'Unable to find your Email address in our system, ensure you typed it correctly' );
					$this->_flashMessenger->resetNamespace();
					$this->_redirect('/login/send-confirmation');	
				}								
			}
		}
	}
	
	/**
	 * Reset the password
	 */
	
	public function resetAction()
	{
		$this->view->title = 'Reset your password';
		
		$form = $this->model->getForm('Login');
		$this->_helper->viewRenderer->setScriptAction('index');
		
		$form->removeElement( 'password' );
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				$data = $form->getValues();
				
				$user = $this->model->getUserByEmail($data['email']);
				
				if ( $user )
				{
					$this->model->forgottenPasswordMail($user);
					$this->_flashMessenger->addMessage( 'We have sent you an email of the new password to the address ' . $user->email );
					$this->_redirect('/');
				} else {
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( 'Unable to find your Email address in our system, ensure you typed it correctly' );
					$this->_flashMessenger->resetNamespace();
					$this->_redirect('/login/reset');
				}
			}
		}
		
	}
	
	/**
	 * Allows user to confirm e-mail address.
	 */
	
	public function confirmAction()
	{
		$code = $this->_getParam("code");
				
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$user = $this->model->getSingleByField( 'confirm', $code );
			
		if ( $user )
		{
			// Code matches, update DB
			$this->model->table->update( array( "status" => "active" ), "id = ".$user['id'] );
			$this->_flashMessenger->addMessage( 'You have now confirmed your account.' );
			$this->_redirect('/');
		} else {
			// Code doesn't match, bitch at user.
			$this->_flashMessenger->setNamespace( 'error' );
			$this->_flashMessenger->addMessage( 'Incorrect confirmation code supplied.' );
			$this->_flashMessenger->resetNamespace();
			$this->_redirect('/');
		}
	}	

	/* These still have to be reworked */
	
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
	
}

