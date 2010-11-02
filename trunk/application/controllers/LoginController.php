<?php

class LoginController extends Recipe_Model_Controller
{

	public function init()
	{
		parent::init();
		$this->_model = new Recipe_Model_User();
		$this->getForm();
	}

	public function indexAction() 
	{

		$this->view->title = 'Login';

		// If we are already logged in we dont need to login again
		if ( $this->_identity )
			$this->_redirect('/');
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->_form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $this->_form->getValues();

				$msg = $this->_model->login( $data['email'], $data['password']);
				$this->_log->debug( 'message ' . sq_brackets( $msg ) );
				
				// Not a valid login, send the msg to the user
				if( $msg !== true ) {
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( $msg );
					$this->_log->debug( 'User ' . sq_brackets( $data['email'] ).' failed login'. var_export( $result, true ) );
					$this->_redirect('/');
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

		$this->_helper->viewRenderer->setScriptAction('index');
		$this->_form->removeElement( 'password' );
		
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->_form->isValid($this->_request->getPost())) {
				$data = $this->_form->getValues();
			
				$user = $this->_model->getUserByEmail($data['email']);
				
				if ($user)
				{
					$this->_model->sendConfirmationEmail($user);
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
		
		$this->_helper->viewRenderer->setScriptAction('index');
		
		$this->_form->removeElement( 'password' );

		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->_form->isValid($this->_request->getPost())) {
				$data = $this->_form->getValues();
				
				$user = $this->_model->getUserByEmail($data['email']);
				
				if ( $user )
				{
					$this->_model->forgottenPasswordMail($user);
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
		
		$user = $this->_model->getSingleByField( 'confirm', $code );
			
		if ( $user )
		{
			// Code matches, update DB
			$this->_model->table->update( array( "status" => "active" ), "id = ".$user['id'] );
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

}

