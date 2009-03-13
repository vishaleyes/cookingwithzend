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
		$this->view->title = 'Your account';
		
		$rowset = $this->model->getByField('id', $this->_identity['id']);
				
		if ( $rowset->current() )
		{
			$user = $rowset->current();
			$this->view->user = $user->toArray();
			$this->view->recipes_count = $user->getRecipeCount();
		}
		
		if ($this->_acl->isAllowed( $role, 'user', 'edit')
		{
			$form = $this->model->getForm('UserAccount');
			$form->populate($user->toArray());
			$this->view->form = $form;
		}
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
