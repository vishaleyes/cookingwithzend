<?php

class UserController extends DefaultController  
{
	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}


	public function newAction()
	{
		$this->view->title = 'Create an account';
		$form = $this->model->getForm('UserNew');
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {
			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {

				$values = $form->getValues();
				$params = array(
					'name'       => $values['name'],
					'email'      => $values['email'],
					'password'   => new Zend_Db_Expr('MD5("'.$values['password'].'")')
				);
		
				try {
					$this->model->table->insert( $params );
					$this->_flashMessenger->addMessage( 'Please check your email for a confirmation link' );
					$this->_log->debug( 'Inserted user ' . $values['email'] );
					$theUser = $this->model->getByField( 'email', $values['email'] );
					$theUser->sendConfirmationEmail();

					$this->_redirect( '/' );
				} catch(Exception $e) {
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( $e->getMessage() );
					$this->_flashMessenger->resetNamespace();
					$this->_log->debug( 'Failed to insert user ' .$values['email'] . ' : ' . var_export( $e, true ) );
					//$this->db->rollback();
					$this->_redirect( '/user/new' );
				}
			}
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
		$this->form->getElement('open_id')->setValue( $this->view->user['openid'] );
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
	
	/*
	 * Executes sendConfirmationEmail function. Used for testing, can be deleted or modified.
	 */
	public function sendconfirmationAction()
	{
		$u = new User();
		$user = $u->getByField( 'id', $this->session->user['id'] );
		$emailResult = $user->sendConfirmationEmail();
	
		if ($emailResult)
		{
			$this->message->addMessage( 'Confirmation e-mail sent.' );
			$this->_redirect('/');
		}
	}
		
	/**
	 * Allows user to confirm e-mail address.
	 */
	
	public function confirmAction()
	{
		$code = $this->_getParam("code");
		$userId = substr( $code, 32, strlen( $code ) - 32 );

		$u = new User();
		$user = $u->getByField( 'id', $this->session->user['id'] );
			
		if ( $code === $user->getVerificationCode() )
		{
			// Code matches, update DB
			$this->db->update( "users", array( "status" => "active" ), "id = $userId" );
			$this->message->addMessage( 'You have now confirmed your account.' );
			$this->_redirect('/');
		} else {
			// Code doesn't match, bitch at user.
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'Incorrect confirmation code supplied.' );
			$this->message->resetNamespace();
			$this->_redirect('/');
		}
		
	}		

}
